<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;

class CurrencyController extends Controller
{
    protected $apiKey;
    protected $apiUrl;
    protected $spread = 0.01;

    public function __construct()
    {
        $this->apiKey = config('services.exchangerates.api_key');
        $this->apiUrl = config('services.exchangerates.api_url');
    }

    /**
     * Set the user's selected currency in the session.
     */
    public function setCurrency(Request $request)
    {
        $request->validate([
            'currency' => 'required|in:USD,EUR,GBP',
        ]);
    
        session(['currency' => $request->currency]);
    
        return redirect()->back(); // Or redirect to 'dashboard' if needed
    }
    
    /**
     * Fetch exchange rates for the base currency.
     */
    public function fetchExchangeRates($baseCurrency)
    {
        $url = config('services.exchangerates.api_url');
    
        $response = Http::get($url);
    
        if ($response->successful()) {
            return $response->json()['rates'] ?? null;
        }
    
        return null;
    }
    

    public function convertToAllCurrencies($amount)
    {
        $baseCurrency = 'USD';
        $targetCurrencies = ['GBP', 'EUR'];

        $rates = $this->fetchExchangeRates($baseCurrency);
        $converted = [];

        if ($rates) {
            foreach ($targetCurrencies as $currency) {
                $rate = $rates[$currency] ?? 1;
                $converted[$currency] = round($amount * $rate * 0.99, 2); // 1% spread
            }
        }

        return $converted;
    }


    /**
     * Get the exchange rate for converting between two currencies.
     */
    public function getExchangeRate($fromCurrency, $toCurrency)
    {
        // If the currencies are the same, no conversion is needed
        if ($fromCurrency === $toCurrency) {
            return 1;
        }

        // Fetch the conversion rate using the Exchange Rates API
        $response = Http::get('https://api.exchangeratesapi.io/latest', [
            'base' => $fromCurrency,
            'symbols' => $toCurrency,
            'access_key' => $this->apiKey,
        ]);

        if (!$response->successful()) {
            return null;  // In case of an error, return null
        }

        $rate = $response->json()['rates'][$toCurrency] ?? null;

        if (!$rate) {
            return null;  // Return null if no rate is found
        }

        // Adjust the rate by the spread (0.01)
        return $rate - ($rate * $this->spread);
    }

    /**
     * Display the user's balance in their selected currency.
     */
    public function displayBalance($amount)
    {
        // Get the selected currency from the session (default to USD)
        $currency = session('currency', 'USD');

        // If the selected currency is USD, return the balance as is
        if ($currency === 'USD') {
            return '$' . number_format($amount, 2);
        }

        // Convert the balance from USD to the selected currency
        $conversionRate = $this->getExchangeRate('USD', $currency);

        if ($conversionRate) {
            $convertedAmount = $amount * $conversionRate;
            $symbol = $this->getCurrencySymbol($currency);
            return $symbol . number_format($convertedAmount, 2);
        }

        return '$' . number_format($amount, 2);  // Fallback to USD if conversion fails
    }

    /**
     * Get the currency symbol based on the selected currency.
     */
    public function getCurrencySymbol($currency)
    {
        $symbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            // Add other currencies as needed
        ];

        return $symbols[$currency] ?? '$';  // Default to USD symbol if not found
    }

    public function formatBalance($amount)
    {
        $currency = session('currency', 'USD');

        if ($currency === 'USD') {
            return '$' . number_format($amount, 2);
        }

        $rate = $this->getExchangeRate('USD', $currency);

        if ($rate) {
            $converted = $amount * $rate;
            $symbol = $this->getCurrencySymbol($currency);
            return $symbol . number_format($converted, 2);
        }

        // Fallback to USD if conversion fails
        return '$' . number_format($amount, 2);
    }

    /**
     * Handle a transfer or balance update, ensuring calculations are done in USD.
     */
    public function transferBalance(Request $request)
    {
        $request->validate([
            'from_account_id' => 'required|exists:accounts,id',
            'to_account_id' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $amount = $request->input('amount');
        $fromAccount = Account::find($request->input('from_account_id'));
        $toAccount = Account::find($request->input('to_account_id'));

        // Convert the amount to USD for internal transfer calculations
        $currency = session('currency', 'USD');
        if ($currency !== 'USD') {
            $conversionRate = $this->getExchangeRate($currency, 'USD');
            if ($conversionRate) {
                $amount = $amount / $conversionRate;  // Convert amount back to USD
            } else {
                return response()->json(['error' => 'Failed to fetch conversion rate'], 500);
            }
        }

        // Perform transfer in USD
        $fromAccount->balance -= $amount;
        $toAccount->balance += $amount;

        dd($fromAccount->balance, $toAccount->balance);
        // Save the changes
        $fromAccount->save();
        $toAccount->save();

        return response()->json(['message' => 'Transfer successful']);
    }
}
