<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CurrencyController;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $accounts = $user->accounts;

        $selectedCurrency = session('currency', 'USD');
        $currencyController = new CurrencyController();

        $convertedPerAccount = [];
        $currencySymbol = '';

        if ($selectedCurrency === 'EUR') {
            $currencySymbol = '€';
        } elseif ($selectedCurrency === 'GBP') {
            $currencySymbol = '£';
        }
        else {
            $currencySymbol = '$';
        }

        foreach ($accounts as $account) {
            $converted = $currencyController->convertToAllCurrencies($account->balance);
            $convertedPerAccount[$account->id] = $converted;
        }

        return view('dashboard', [
            'accounts' => $accounts,
            'selectedCurrency' => $selectedCurrency,
            'convertedPerAccount' => $convertedPerAccount,
            'currencySymbol' => $currencySymbol,
        ]);
    }
}
