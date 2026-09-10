<?php

namespace App\Services;

use App\Models\Account;
use Carbon\Carbon;

class FinancialForecastService
{
    /**
     * Calcule le solde prévisionnel d'un compte
     * jusqu'à la fin du mois.
     */
    public function forecastAccountBalance(
        Account $account,
        ?Carbon $date = null
    ): float {
        $date = $date ?? today();

        $endOfMonth = $date->copy()->endOfMonth();

        $forecast = (float) $account->balance;

        $recurringTransactions = $account->recurringTransactions()
            ->where('is_active', true)
            ->get();

        foreach ($recurringTransactions as $recurring) {

            $occurrence = Carbon::parse(
                $recurring->next_occurrence
            );

            while ($occurrence->lte($endOfMonth)) {

                if ($occurrence->gte($date)) {

                    if ($recurring->type === 'income') {
                        $forecast += (float) $recurring->amount;
                    }

                    if ($recurring->type === 'expense') {
                        $forecast -= (float) $recurring->amount;
                    }
                }

                $occurrence = $this->nextOccurrence(
                    $occurrence,
                    $recurring->frequency
                );
            }
        }

        return $forecast;
    }

    /**
     * Calcule les revenus récurrents restant à venir
     * jusqu'à la fin du mois.
     */
    public function upcomingRecurringIncome(
        Account $account,
        ?Carbon $date = null
    ): float {
        return $this->calculateUpcomingAmount(
            $account,
            'income',
            $date
        );
    }

    /**
     * Calcule les dépenses récurrentes restant à venir
     * jusqu'à la fin du mois.
     */
    public function upcomingRecurringExpenses(
        Account $account,
        ?Carbon $date = null
    ): float {
        return $this->calculateUpcomingAmount(
            $account,
            'expense',
            $date
        );
    }

    /**
     * Calcule le montant total des récurrences
     * d'un type donné jusqu'à la fin du mois.
     */
    private function calculateUpcomingAmount(
        Account $account,
        string $type,
        ?Carbon $date = null
    ): float {
        $date = $date ?? today();

        $endOfMonth = $date->copy()->endOfMonth();

        $total = 0;

        $recurringTransactions = $account->recurringTransactions()
            ->where('is_active', true)
            ->where('type', $type)
            ->get();

        foreach ($recurringTransactions as $recurring) {

            $occurrence = Carbon::parse(
                $recurring->next_occurrence
            );

            while ($occurrence->lte($endOfMonth)) {

                if ($occurrence->gte($date)) {
                    $total += (float) $recurring->amount;
                }

                $occurrence = $this->nextOccurrence(
                    $occurrence,
                    $recurring->frequency
                );
            }
        }

        return $total;
    }

    /**
     * Calcule la prochaine occurrence d'une récurrence.
     */
    private function nextOccurrence(
        Carbon $date,
        string $frequency
    ): Carbon {
        return match ($frequency) {
            'daily' => $date->copy()->addDay(),

            'weekly' => $date->copy()->addWeek(),

            'monthly' => $date->copy()->addMonthNoOverflow(),

            'yearly' => $date->copy()->addYearNoOverflow(),

            default => $date->copy(),
        };
    }
}