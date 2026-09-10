<?php

namespace App\Http\Controllers;

use App\Services\AccountAnalysisService;
use App\Services\FinancialForecastService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(
        FinancialForecastService $forecastService,
        AccountAnalysisService $analysisService
    ): View {
        $user = auth()->user();

        $accounts = $user->accounts()
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Analyse des comptes
        |--------------------------------------------------------------------------
        */

        $accountForecasts = $accounts->map(function ($account) use (
            $forecastService,
            $analysisService
        ) {
            $upcomingIncome = $forecastService
                ->upcomingRecurringIncome($account);

            $upcomingExpenses = $forecastService
                ->upcomingRecurringExpenses($account);

            $forecastBalance = $forecastService
                ->forecastAccountBalance($account);

            $monthlyExpenses = $analysisService
                ->monthlyExpenseTotal($account);

            return [
                'account' => $account,

                'real_balance' => (float) $account->balance,

                'upcoming_income' => $upcomingIncome,

                'upcoming_expenses' => $upcomingExpenses,

                'forecast_balance' => $forecastBalance,

                'monthly_expenses' => $monthlyExpenses,
            ];
        });


        /*
        |--------------------------------------------------------------------------
        | Vue globale
        |--------------------------------------------------------------------------
        */

        $totalRealBalance = $accountForecasts->sum(
            'real_balance'
        );

        $totalUpcomingIncome = $accountForecasts->sum(
            'upcoming_income'
        );

        $totalUpcomingExpenses = $accountForecasts->sum(
            'upcoming_expenses'
        );

        $totalForecastBalance = $accountForecasts->sum(
            'forecast_balance'
        );

        $totalMonthlyExpenses = $accountForecasts->sum(
            'monthly_expenses'
        );


        /*
        |--------------------------------------------------------------------------
        | Objectifs d'épargne
        |--------------------------------------------------------------------------
        */

        $savingsGoals = $user->savingsGoals()
            ->with('contributions')
            ->where('is_completed', false)
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($goal) {

                $currentAmount = (float) $goal
                    ->contributions
                    ->sum('amount');

                $targetAmount = (float) $goal->target_amount;

                $percentage = $targetAmount > 0
                    ? min(
                        100,
                        round(
                            ($currentAmount / $targetAmount) * 100,
                            1
                        )
                    )
                    : 0;

                return [
                    'goal' => $goal,

                    'current_amount' => $currentAmount,

                    'target_amount' => $targetAmount,

                    'percentage' => $percentage,
                ];
            });


        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        return view('dashboard', [
            'accounts' => $accounts,
            'accountForecasts' => $accountForecasts,

            'totalRealBalance' => $totalRealBalance,
            'totalUpcomingIncome' => $totalUpcomingIncome,
            'totalUpcomingExpenses' => $totalUpcomingExpenses,
            'totalForecastBalance' => $totalForecastBalance,
            'totalMonthlyExpenses' => $totalMonthlyExpenses,

            'savingsGoals' => $savingsGoals,
        ]);
    }
}