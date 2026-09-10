<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\AccountAnalysisService;
use App\Services\FinancialForecastService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(
        AccountAnalysisService $analysisService,
        FinancialForecastService $forecastService
    ): View {
        $accounts = auth()->user()
            ->accounts()
            ->latest()
            ->get();

        $accountAnalyses = $accounts->map(function (Account $account) use (
            $analysisService,
            $forecastService
        ) {
            $expenses = $analysisService->topMonthlyExpenses($account);

            $monthlyExpenseTotal = $analysisService
                ->monthlyExpenseTotal($account);

            $upcomingIncome = $forecastService
                ->upcomingRecurringIncome($account);

            $upcomingExpenses = $forecastService
                ->upcomingRecurringExpenses($account);

            $forecastBalance = $forecastService
                ->forecastAccountBalance($account);

            return [
                'account' => $account,

                'real_balance' => (float) $account->balance,

                'expenses' => $expenses,

                'monthly_expense_total' => $monthlyExpenseTotal,

                'upcoming_income' => $upcomingIncome,

                'upcoming_expenses' => $upcomingExpenses,

                'forecast_balance' => $forecastBalance,
            ];
        });

        return view('accounts.index', compact(
            'accounts',
            'accountAnalyses'
        ));
    }

    public function create(): View
    {
        return view('accounts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:current,savings,joint,other'],
            'balance' => ['required', 'numeric', 'min:0'],
            'is_shared' => ['boolean'],
        ]);

        $validated['is_shared'] = $request->boolean('is_shared');

        auth()->user()->accounts()->create($validated);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Compte créé avec succès.');
    }

    public function show(Account $account): View
    {
        $this->authorizeAccount($account);

        return view('accounts.show', compact('account'));
    }

    public function edit(Account $account): View
    {
        $this->authorizeAccount($account);

        return view('accounts.edit', compact('account'));
    }

    public function update(
        Request $request,
        Account $account
    ): RedirectResponse {
        $this->authorizeAccount($account);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:current,savings,joint,other'],
            'is_shared' => ['boolean'],
        ]);

        $validated['is_shared'] = $request->boolean('is_shared');

        $account->update($validated);

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Compte modifié avec succès.');
    }

    public function destroy(Account $account): RedirectResponse
    {
        $this->authorizeAccount($account);

        $account->delete();

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Compte supprimé avec succès.');
    }

    private function authorizeAccount(Account $account): void
    {
        abort_unless(
            $account->user_id === auth()->id(),
            404
        );
    }
}