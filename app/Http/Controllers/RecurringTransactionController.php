<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\RecurringTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;


class RecurringTransactionController extends Controller
{
    public function index(): View
{
    $recurringTransactions = auth()->user()
        ->recurringTransactions()
        ->with(['account', 'category'])
        ->orderBy('next_occurrence')
        ->get();

    $dueRecurringTransactions = $recurringTransactions
        ->filter(function ($recurring) {
            return $recurring->is_active
                && $recurring->next_occurrence->lte(today());
        });

    return view(
        'recurring-transactions.index',
        compact(
            'recurringTransactions',
            'dueRecurringTransactions'
        )
    );
}

    public function create(): View
    {
        $accounts = auth()->user()
            ->accounts()
            ->orderBy('name')
            ->get();

        $categories = auth()->user()
            ->categories()
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view(
            'recurring-transactions.create',
            compact('accounts', 'categories')
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'account_id' => ['required', 'integer'],
            'category_id' => ['nullable', 'integer'],
            'type' => ['required', 'in:expense,income'],
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'frequency' => ['required', 'in:daily,weekly,monthly,yearly'],
            'next_occurrence' => ['required', 'date'],
        ]);

        $user = auth()->user();

        $account = $user->accounts()->findOrFail(
            $validated['account_id']
        );

       if (!empty($validated['category_id'])) {
    $user->categories()
        ->where('type', $validated['type'])
        ->findOrFail($validated['category_id']);
}

        $user->recurringTransactions()->create([
            'account_id' => $account->id,
            'category_id' => $validated['category_id'] ?? null,
            'type' => $validated['type'],
            'name' => $validated['name'],
            'amount' => $validated['amount'],
            'frequency' => $validated['frequency'],
            'next_occurrence' => $validated['next_occurrence'],
            'is_active' => true,
        ]);

        return redirect()
            ->route('recurring-transactions.index')
            ->with('success', 'Transaction récurrente créée avec succès.');
    }

    public function edit(RecurringTransaction $recurringTransaction): View
    {
        $this->authorizeRecurringTransaction($recurringTransaction);

        $accounts = auth()->user()
            ->accounts()
            ->orderBy('name')
            ->get();

        $categories = auth()->user()
            ->categories()
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view(
            'recurring-transactions.edit',
            compact(
                'recurringTransaction',
                'accounts',
                'categories'
            )
        );
    }

    public function update(
        Request $request,
        RecurringTransaction $recurringTransaction
    ): RedirectResponse {
        $this->authorizeRecurringTransaction($recurringTransaction);

        $validated = $request->validate([
            'account_id' => ['required', 'integer'],
            'category_id' => ['nullable', 'integer'],
            'type' => ['required', 'in:expense,income'],
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'frequency' => ['required', 'in:daily,weekly,monthly,yearly'],
            'next_occurrence' => ['required', 'date'],
            'is_active' => ['boolean'],
        ]);

        $user = auth()->user();

        $account = $user->accounts()->findOrFail(
            $validated['account_id']
        );

        if (!empty($validated['category_id'])) {
            $user->categories()
                ->where('type', $validated['type'])
                ->findOrFail($validated['category_id']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $recurringTransaction->update([
            'account_id' => $account->id,
            'category_id' => $validated['category_id'] ?? null,
            'type' => $validated['type'],
            'name' => $validated['name'],
            'amount' => $validated['amount'],
            'frequency' => $validated['frequency'],
            'next_occurrence' => $validated['next_occurrence'],
            'is_active' => $validated['is_active'],
        ]);

        return redirect()
            ->route('recurring-transactions.index')
            ->with('success', 'Transaction récurrente modifiée avec succès.');
    }

    public function destroy(
        RecurringTransaction $recurringTransaction
    ): RedirectResponse {
        $this->authorizeRecurringTransaction($recurringTransaction);

        $recurringTransaction->delete();

        return redirect()
            ->route('recurring-transactions.index')
            ->with('success', 'Transaction récurrente supprimée avec succès.');
    }

    
    private function authorizeRecurringTransaction(
        RecurringTransaction $recurringTransaction
    ): void {
        abort_unless(
            $recurringTransaction->user_id === auth()->id(),
            404
        );
    }
}