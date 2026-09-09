<?php

namespace App\Services;

use App\Models\Account;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use Carbon\Carbon;

class FinancialForecastService
{
    /**
     * Calcule le solde prévu d'un compte à la fin du mois.
     *
     * Le solde réel du compte n'est jamais modifié.
     */
    public function forecastAccountBalance(
        Account $account,
        ?Carbon $date = null
    ): float {
        $date = $date ?? today();

        $endOfMonth = $date->copy()->endOfMonth();

        $forecast = (float) $account->balance;

        // Transactions réelles déjà effectuées :
        // elles sont déjà incluses dans le solde actuel,
        // donc on ne les ajoute pas une deuxième fois.

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
                    } else {
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