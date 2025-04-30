<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = User::where('email', $request->email)->firstOrFail();

        // Generate 2FA code
        $user->two_factor_code = rand(100000, 999999);
        $user->two_factor_expires_at = now()->addMinutes(10);
        $user->save();

        // Send 2FA code via email
        Mail::raw("Your 2FA code is: {$user->two_factor_code}", function ($message) use ($user) {
            $message->to($user->email)->subject('Your 2FA Code');
        });

        // Log out and redirect to 2FA input
        Auth::logout();
        session(['2fa:user:id' => $user->id]);

        return redirect()->route('2fa.index');
    }

    public function show2faForm(): View
    {
        return view('auth.2fa');
    }

    public function verify2fa(Request $request): RedirectResponse
    {
        $request->validate([
            'two_factor_code' => 'required|numeric',
        ]);

        $user = User::findOrFail(session('2fa:user:id'));

        if (
            $user->two_factor_code === $request->two_factor_code &&
            now()->lt($user->two_factor_expires_at)
        ) {
            // Clear 2FA session
            session()->forget('2fa:user:id');

            // Clear 2FA fields
            $user->two_factor_code = null;
            $user->two_factor_expires_at = null;
            $user->save();

            // Log in the user
            Auth::login($user);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['two_factor_code' => 'Invalid or expired code']);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
