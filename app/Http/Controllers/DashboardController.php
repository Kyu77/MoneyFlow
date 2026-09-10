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

            $monthlyIncome = $analysisService
                ->monthlyIncomeTotal($account);

            $monthlyExpenses = $analysisService
                ->monthlyExpenseTotal($account);

            return [
                'account' => $account,

                // Solde réellement disponible maintenant
                'real_balance' => (float) $account->balance,

                // Revenus / dépenses réellement enregistrés ce mois
                'monthly_income' => $monthlyIncome,
                'monthly_expenses' => $monthlyExpenses,

                // Mouvements récurrents à venir
                'upcoming_income' => $upcomingIncome,
                'upcoming_expenses' => $upcomingExpenses,

                // Prévision de fin de mois
                'forecast_balance' => $forecastBalance,
            ];
        });

        // =========================================================
        // TOTAUX GLOBAUX
        // =========================================================

        $totalRealBalance = $accountForecasts
            ->sum('real_balance');

        $totalMonthlyIncome = $accountForecasts
            ->sum('monthly_income');

        $totalMonthlyExpenses = $accountForecasts
            ->sum('monthly_expenses');

        $totalUpcomingIncome = $accountForecasts
            ->sum('upcoming_income');

        $totalUpcomingExpenses = $accountForecasts
            ->sum('upcoming_expenses');

        $totalForecastBalance = $accountForecasts
            ->sum('forecast_balance');


        // =========================================================
        // OBJECTIFS D'ÉPARGNE
        // =========================================================

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


        return view('dashboard', [

            'accounts' => $accounts,

            'accountForecasts' => $accountForecasts,

            'totalRealBalance' => $totalRealBalance,

            'totalMonthlyIncome' => $totalMonthlyIncome,

            'totalMonthlyExpenses' => $totalMonthlyExpenses,

            'totalUpcomingIncome' => $totalUpcomingIncome,

            'totalUpcomingExpenses' => $totalUpcomingExpenses,

            'totalForecastBalance' => $totalForecastBalance,

            'savingsGoals' => $savingsGoals,

        ]);
    }
}

