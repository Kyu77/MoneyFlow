<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\RecurringTransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SavingsGoalController;
use App\Http\Controllers\SavingsContributionController;


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
    Route::get('transfers/create', [TransferController::class, 'create'])->name('transfers.create');
    Route::post('transfers', [TransferController::class, 'store'])->name('transfers.store');
    Route::resource('recurring-transactions',RecurringTransactionController::class)->except(['show']);
    Route::resource('savings-goals',SavingsGoalController::class)->except(['show']);
    Route::get('savings-goals/{savingsGoal}/contributions/create',[SavingsContributionController::class, 'create'])->name('savings-goals.contributions.create');
    Route::post('savings-goals/{savingsGoal}/contributions',[SavingsContributionController::class, 'store'])->name('savings-goals.contributions.store');
    Route::delete('savings-contributions/{savingsContribution}',[SavingsContributionController::class, 'destroy'])->name('savings-contributions.destroy');
    Route::get('transactions/create',[TransactionController::class, 'createGlobal'])->name('transactions.create');
    Route::post('transactions',[TransactionController::class, 'storeGlobal'])->name('transactions.store');
});

require __DIR__.'/auth.php';
