<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Account;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Get the logged-in user

        return view('dashboard', [
            'user' => $user,
            'accounts' => $user->accounts,
        ]);
    }
}

