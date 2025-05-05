<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\CurrencyController;

class TransferController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $accounts = $user->accounts;

        // Currency symbols array
        $currencySymbols = [
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
        ];

        return view('transfer.create', compact('accounts', 'currencySymbols'));
    }

    public function store(Request $request)
    {
        // Validate incoming request
        try {
            $validated = $request->validate([
                'from_account_id' => 'required|exists:accounts,id',
                'to_account_number' => 'required|exists:accounts,number',
                'amount' => 'required|numeric|min:0.01',
                'currency' => 'required|in:USD,EUR,GBP',
                'description' => 'nullable|string|max:255',
            ]);
        } catch (ValidationException $e) {
            dd('Validation failed!', $e->errors());
        }

        $fromAccount = Account::findOrFail($validated['from_account_id']);
        $toAccount = Account::where('number', $validated['to_account_number'])->firstOrFail();

        if ($fromAccount->user_id !== Auth::id()) {
            abort(403, 'Unauthorized transfer.');
        }

        // Prevent transfer to the same account
        if ($fromAccount->id === $toAccount->id) {
            return back()->withErrors(['to_account_number' => 'Cannot transfer to the same account.']);
        }

        if ($fromAccount->balance < $validated['amount']) {
            return back()->withErrors(['amount' => 'Insufficient balance.']);
        }

        // Currency conversion using CurrencyController
        $currencyController = new CurrencyController();
        
        $amountToDeduct = $validated['amount'];
        $amountToCredit = $validated['amount'];

        if ($fromAccount->currency !== $toAccount->currency) {
            $conversionRate = $currencyController->getExchangeRate($fromAccount->currency, $toAccount->currency);
            $amountToCredit = round($amountToDeduct * $conversionRate, 2);
        }

        try {
            DB::transaction(function () use ($fromAccount, $toAccount, $amountToDeduct, $amountToCredit, $validated) {
                $fromAccount->balance -= $amountToDeduct;
                $fromAccount->save();

                $toAccount->balance += $amountToCredit;
                $toAccount->save();

                $now = now();

                $spread = 0.01;
                $spreadAmount = 0.00;

                if ($validated['currency'] !== 'USD') {
                    $spreadAmount = $amountToDeduct * $spread;
                    $amountToDeduct -= $spreadAmount; 
                }

                Transaction::create([
                    'account_id' => $fromAccount->id,
                    'type' => 'transfer',
                    'amount' => $amountToDeduct,
                    'spread_amount' => $spreadAmount,
                    'from_account_number' => $fromAccount->number,
                    'to_account_number' => $toAccount->number,
                    'description' => $validated['description'],
                    'transaction_date' => $now,
                ]);

            });
        } catch (Exception $e) {
            dd('Transaction failed:', $e->getMessage());
        }

        return redirect()->route('dashboard')->with('success', 'Transfer completed successfully.');
    }
}
