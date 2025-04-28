<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;


class TransferController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $accounts = $user->accounts;

        return view('transfer.create', compact('accounts'));
    }


    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'from_account_id' => 'required|exists:accounts,id',
                'to_account_number' => 'required|exists:accounts,number',
                'amount' => 'required|numeric|min:0.01',
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
    
        if ($fromAccount->balance < $validated['amount']) {
            return back()->withErrors(['amount' => 'Insufficient balance.']);
        }
    
        try {
            DB::transaction(function () use ($fromAccount, $toAccount, $validated) {
                $amount = $validated['amount'];
    
                // Subtract from sender
                $fromAccount->balance -= $amount;
                $fromAccount->save();
    
                // Add to receiver
                $toAccount->balance += $amount;
                $toAccount->save();
    
                $now = now();
    
                // Log transaction
                Transaction::create([
                    'account_id' => $fromAccount->id,
                    'type' => 'transfer',
                    'amount' => $amount,
                    'description' => "Transferred £{$amount} to account #{$toAccount->account_number}",
                    'transaction_date' => $now,
                ]);
            });
        } catch (Exception $e) {
            dd('Transaction failed:', $e->getMessage());
        }
    
        return redirect()->route('dashboard')->with('success', 'Transfer completed successfully.');
    }
    
    
}
