<?php

namespace App\Services;

use App\Models\RecurringTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RecurringTransactionService
{
    /**
     * Confirme une échéance :
     * - crée la transaction réelle
     * - modifie le solde du compte
     * - avance la prochaine échéance
     */
    public function confirm(RecurringTransaction $recurringTransaction): void
    {
        DB::transaction(function () use ($recurringTransaction) {

            $account = $recurringTransaction->account;

            $transaction = $account->transactions()->create([
                'category_id' => $recurringTransaction->category_id,
                'type' => $recurringTransaction->type,
                'amount' => $recurringTransaction->amount,
                'description' => $recurringTransaction->name,
                'transaction_date' => $recurringTransaction->next_occurrence,
            ]);

            if ($transaction->type === 'income') {
                $account->increment(
                    'balance',
                    $transaction->amount
                );
            } else {
                $account->decrement(
                    'balance',
                    $transaction->amount
                );
            }

            $this->advanceNextOccurrence($recurringTransaction);
        });
    }

    /**
     * Ignore une échéance :
     * - aucune transaction n'est créée
     * - la prochaine échéance est avancée
     */
    public function ignore(RecurringTransaction $recurringTransaction): void
    {
        DB::transaction(function () use ($recurringTransaction) {
            $this->advanceNextOccurrence($recurringTransaction);
        });
    }

    /**
     * Avance la prochaine échéance selon la fréquence.
     */
    private function advanceNextOccurrence(
        RecurringTransaction $recurringTransaction
    ): void {
        $date = Carbon::parse(
            $recurringTransaction->next_occurrence
        );

        switch ($recurringTransaction->frequency) {

            case 'daily':
                $date->addDay();
                break;

            case 'weekly':
                $date->addWeek();
                break;

            case 'monthly':
                $date->addMonthNoOverflow();
                break;

            case 'yearly':
                $date->addYearNoOverflow();
                break;
        }

        $recurringTransaction->update([
            'next_occurrence' => $date->toDateString(),
        ]);
    }
}