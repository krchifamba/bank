<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CurrencyController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('/transfer', [TransferController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('transfer.store');


Route::get('/transactions', [TransactionController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('transactions.index');

Route::get('/admin/accounts', [AccountController::class, 'index'])
    ->middleware('auth') 
    ->name('admin.accounts');

Route::post('/admin/accounts/create', [AccountController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('admin.accounts.create');


Route::get('/2fa', [AuthenticatedSessionController::class, 'show2faForm'])
    ->name('2fa.index');

Route::post('/2fa', [AuthenticatedSessionController::class, 'verify2fa'])
    ->name('2fa.verify');

Route::post('/set-currency', [CurrencyController::class, 'setCurrency'])
    ->name('currency.set');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
