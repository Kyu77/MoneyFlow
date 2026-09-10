<?php

namespace App\Services;

use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AccountAnalysisService
{
    /**
     * Retourne les dépenses réellement passées
     * du mois en cours, regroupées par catégorie.
     */
    public function monthlyExpensesByCategory(
        Account $account,
        ?Carbon $date = null
    ): Collection {
        $date = $date ?? today();

        $startOfMonth = $date->copy()->startOfMonth();
        $today = $date->copy()->startOfDay();

        $transactions = $account->transactions()
            ->with('category')
            ->where('type', 'expense')
            ->whereNull('transfer_id')
            ->whereNotNull('category_id')
            ->whereBetween('transaction_date', [
                $startOfMonth->toDateString(),
                $today->toDateString(),
            ])
            ->get();

        return $transactions
            ->groupBy('category_id')
            ->map(function (Collection $categoryTransactions) {
                $category = $categoryTransactions->first()->category;

                return [
                    'category' => $category,
                    'amount' => (float) $categoryTransactions->sum('amount'),
                ];
            })
            ->sortByDesc('amount')
            ->values();
    }

    /**
     * Retourne les principales catégories de dépenses
     * avec une catégorie "Autres" si nécessaire.
     */
    public function topMonthlyExpenses(
        Account $account,
        ?Carbon $date = null,
        int $limit = 4
    ): Collection {
        $expenses = $this->monthlyExpensesByCategory($account, $date);

        if ($expenses->isEmpty()) {
            return collect();
        }

        $total = $expenses->sum('amount');

        $topExpenses = $expenses->take($limit);

        $otherAmount = $expenses
            ->skip($limit)
            ->sum('amount');

        if ($otherAmount > 0) {
            $topExpenses->push([
                'category' => null,
                'amount' => $otherAmount,
                'is_other' => true,
            ]);
        }

        return $topExpenses->map(function (array $expense) use ($total) {
            $expense['percentage'] = $total > 0
                ? round(($expense['amount'] / $total) * 100, 1)
                : 0;

            $expense['is_other'] = $expense['is_other'] ?? false;

            return $expense;
        });
    }

    /**
     * Retourne le total des dépenses réellement passées
     * durant le mois en cours.
     */
    public function monthlyExpenseTotal(
        Account $account,
        ?Carbon $date = null
    ): float {
        $date = $date ?? today();

        return (float) $account->transactions()
            ->where('type', 'expense')
            ->whereNull('transfer_id')
            ->whereBetween('transaction_date', [
                $date->copy()->startOfMonth()->toDateString(),
                $date->copy()->toDateString(),
            ])
            ->sum('amount');
    }

    /**
     * Retourne le total des revenus réellement passés
     * durant le mois en cours.
     */
    public function monthlyIncomeTotal(
        Account $account,
        ?Carbon $date = null
    ): float {
        $date = $date ?? today();

        return (float) $account->transactions()
            ->where('type', 'income')
            ->whereNull('transfer_id')
            ->whereBetween('transaction_date', [
                $date->copy()->startOfMonth()->toDateString(),
                $date->copy()->toDateString(),
            ])
            ->sum('amount');
    }
}