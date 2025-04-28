<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function index()
    {
        // Get all transactions for the authenticated user's accounts
        $transactions = Transaction::whereIn('account_id', Auth::user()->accounts->pluck('id'))
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }
}
