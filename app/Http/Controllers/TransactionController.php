<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Account $account): View
    {
        $this->authorizeAccount($account);

        $transactions = $account->transactions()
            ->with('category')
            ->latest('transaction_date')
            ->latest()
            ->get();

        return view('transactions.index', compact('account', 'transactions'));
    }

   public function create(Account $account): View
{
    $this->authorizeAccount($account);

    $categories = auth()->user()
        ->categories()
        ->orderBy('name')
        ->get();

    return view('transactions.create', compact('account', 'categories'));
}

    public function store(Request $request, Account $account): RedirectResponse
    {
        $this->authorizeAccount($account);

        $validated = $request->validate([
            'category_id' => ['nullable', 'integer'],
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'description' => ['nullable', 'string', 'max:255'],
            'transaction_date' => ['required', 'date'],
        ]);

        if (!empty($validated['category_id'])) {
            auth()->user()
                ->categories()
                ->findOrFail($validated['category_id']);
        }

        $transaction = DB::transaction(function () use ($account, $validated) {
        $transaction = $account->transactions()->create($validated);

        if ($transaction->type === 'income') {
            $account->increment('balance', $transaction->amount);
        } else {
            $account->decrement('balance', $transaction->amount);
        }

        return $transaction;
    });
        return redirect()
            ->route('accounts.transactions.index', $account)
            ->with('success', 'Transaction ajoutée avec succès.');
    }

    public function show(Account $account, Transaction $transaction): View
    {
        $this->authorizeTransaction($account, $transaction);

        $transaction->load('category');

        return view('transactions.show', compact('account', 'transaction'));
    }

    public function edit(Account $account, Transaction $transaction): View
    {
        $this->authorizeTransaction($account, $transaction);

        $categories = auth()->user()
            ->categories()
            ->where('type', $transaction->type)
            ->orderBy('name')
            ->get();

        return view('transactions.edit', compact(
            'account',
            'transaction',
            'categories'
        ));
    }

    public function update(
        Request $request,
        Account $account,
        Transaction $transaction
    ): RedirectResponse {
        $this->authorizeTransaction($account, $transaction);

        $validated = $request->validate([
            'category_id' => ['nullable', 'integer'],
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'description' => ['nullable', 'string', 'max:255'],
            'transaction_date' => ['required', 'date'],
        ]);

        DB::transaction(function () use ($transaction, $validated) {

        $this->reverseBalanceImpact($transaction);

        if (!empty($validated['category_id'])) {
            auth()->user()
                ->categories()
                ->findOrFail($validated['category_id']);
        }

        $transaction->update($validated);

        $this->applyBalanceImpact($transaction);
    });

        return redirect()
            ->route('accounts.transactions.index', $account)
            ->with('success', 'Transaction modifiée avec succès.');
    }

   public function destroy(Account $account,Transaction $transaction): RedirectResponse 
   {
    $this->authorizeTransaction($account, $transaction);

    DB::transaction(function () use ($transaction) {
        $this->reverseBalanceImpact($transaction);

        $transaction->delete();
    });

    return redirect()
        ->route('accounts.transactions.index', $account)
        ->with('success', 'Transaction supprimée avec succès.');
}

    private function applyBalanceImpact(Transaction $transaction): void
    {
        if ($transaction->type === 'income') {
            $transaction->account->increment('balance', $transaction->amount);
        } else {
            $transaction->account->decrement('balance', $transaction->amount);
        }
    }

    private function reverseBalanceImpact(Transaction $transaction): void
    {
        if ($transaction->type === 'income') {
            $transaction->account->decrement('balance', $transaction->amount);
        } else {
            $transaction->account->increment('balance', $transaction->amount);
        }
    }

    private function authorizeAccount(Account $account): void
    {
        abort_unless(
            $account->user_id === auth()->id(),
            404
        );
    }

    private function authorizeTransaction(
        Account $account,
        Transaction $transaction
    ): void {
        $this->authorizeAccount($account);

        abort_unless(
            $transaction->account_id === $account->id,
            404
        );
    }
}