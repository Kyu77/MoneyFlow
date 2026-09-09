<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\RecurringTransactionController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('recurring-transactions',RecurringTransactionController::class)->except(['show']);
    Route::resource('accounts', AccountController::class);
    Route::resource('accounts.transactions', TransactionController::class);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::get('transfers/create', [TransferController::class, 'create'])
    ->name('transfers.create');
    Route::post('transfers', [TransferController::class, 'store'])
    ->name('transfers.store');
    Route::resource('recurring-transactions',RecurringTransactionController::class)->except(['show']);
});

require __DIR__.'/auth.php';
