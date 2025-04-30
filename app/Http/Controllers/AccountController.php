<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Auth::user()?->is_admin, 403, 'Unauthorized');

        $query = DB::table('accounts')
            ->join('users', 'accounts.user_id', '=', 'users.id')
            ->select(
                'accounts.id',
                'accounts.number',
                'accounts.balance',
                'accounts.type',
                'users.address',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as name"),
                'users.email'
            );

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"), 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('accounts.number', 'like', "%{$search}%");
            });
        }

        if ($type = $request->input('type')) {
            $query->where('accounts.type', $type);
        }

        return view('admin.accounts', [
            'accounts' => $query->paginate(10)->appends($request->query())
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name'  => 'required|string|max:255',
                'email'      => 'required|string|email|max:255|unique:users',
                'address'    => 'required|string|max:255',
                'dob'        => 'required|date',
                'type'       => 'required|in:savings,current',
            ]);

            $user = User::create([
                'first_name'    => $validated['first_name'],
                'last_name'     => $validated['last_name'],
                'email'         => $validated['email'],
                'address'       => $validated['address'],
                'date_of_birth' => $validated['dob'],
                'password'      => Hash::make('password'),
                'is_admin'      => false,
            ]);

            Account::create([
                'user_id' => $user->id,
                'number'  => mt_rand(1000000000, 9999999999),
                'balance' => 10000,
                'type'    => $validated['type'],
            ]);

            return redirect()->route('admin.accounts')->with('success', 'Account created successfully!');
        } catch (Exception $e) {
            report($e);
            return back()->withErrors('Failed to create account: ' . $e->getMessage());
        }
    }
}
