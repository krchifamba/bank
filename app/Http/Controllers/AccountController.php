<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Hash;


class AccountController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user->is_admin;
        if (!$isAdmin) {
            abort(403, 'Unauthorized');
        }

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

        // Search functionality
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"), 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('accounts.number', 'like', "%{$search}%");
            });
        }

        // Filter by account type
        if ($type = $request->input('type')) {
            $query->where('accounts.type', $type);
        }

        $accounts = $query->paginate(10)->appends($request->query());

        return view('admin.accounts', compact('accounts'));
    }

    public function store(Request $request)
    {
        
        try {
            $validatedData = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name'  => ['required', 'string', 'max:255'],
                'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'address'    => ['required', 'string', 'max:255'],
                'dob'        => ['required', 'date'], 
                'type'       => ['required', 'in:savings,current'],
            ]);
    
            // Create the user
            $user = new User();
            $user->first_name    = $validatedData['first_name'];
            $user->last_name     = $validatedData['last_name'];
            $user->email         = $validatedData['email'];
            $user->address       = $validatedData['address'];
            $user->date_of_birth = $validatedData['dob'];
            $user->password      = Hash::make('password'); // Set a default password
            $user->is_admin      = false;
            $user->save();

            // Create the account
            $account = new Account();
            $account->user_id = $user->id;
            $account->number = mt_rand(1000000000, 9999999999); // Random account number
            $account->balance = 10000; // Default balance
            $account->type = $validatedData['type'];
            $account->save();
    
            return redirect()->route('admin.accounts')->with('success', 'Account created successfully!');
        } catch (Exception $e) {
            dd('Error creating account:', $e->getMessage());
        }

        // Create new account
        // Account::create([
        //     'user_id' => $user->id,
        //     'number'  => mt_rand(1000000000, 9999999999), // Random 10-digit account number
        //     'balance' => 10000,
        //     'type'    => $validated['type'],
        //     'address' => $validated['address'], 
        // ]);

        return redirect()->route('admin.accounts')->with('success', 'Account created successfully.');
    }
}
