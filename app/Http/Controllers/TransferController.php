<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TransferController extends Controller
{
    public function create(): View
    {
        $accounts = auth()->user()
            ->accounts()
            ->orderBy('name')
            ->get();

        return view('transfers.create', compact('accounts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'from_account_id' => ['required', 'integer'],
            'to_account_id' => ['required', 'integer', 'different:from_account_id'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'description' => ['nullable', 'string', 'max:255'],
            'transaction_date' => ['required', 'date'],
        ]);

        $user = auth()->user();

        $fromAccount = $user->accounts()->findOrFail(
            $validated['from_account_id']
        );

        $toAccount = $user->accounts()->findOrFail(
            $validated['to_account_id']
        );

        if ((float) $fromAccount->balance < (float) $validated['amount']) {
            return back()
                ->withErrors([
                    'amount' => 'Le solde du compte source est insuffisant.',
                ])
                ->withInput();
        }

        DB::transaction(function () use (
            $fromAccount,
            $toAccount,
            $validated
        ) {
            $transferId = (string) Str::uuid();

            $fromAccount->transactions()->create([
                'category_id' => null,
                'type' => 'expense',
                'amount' => $validated['amount'],
                'description' => $validated['description']
                    ? 'Transfert vers ' . $toAccount->name . ' — ' . $validated['description']
                    : 'Transfert vers ' . $toAccount->name,
                'transaction_date' => $validated['transaction_date'],
                'transfer_id' => $transferId,
            ]);

            $toAccount->transactions()->create([
                'category_id' => null,
                'type' => 'income',
                'amount' => $validated['amount'],
                'description' => $validated['description']
                    ? 'Transfert depuis ' . $fromAccount->name . ' — ' . $validated['description']
                    : 'Transfert depuis ' . $fromAccount->name,
                'transaction_date' => $validated['transaction_date'],
                'transfer_id' => $transferId,
            ]);

            $fromAccount->decrement('balance', $validated['amount']);
            $toAccount->increment('balance', $validated['amount']);
        });

        return redirect()
            ->route('accounts.index')
            ->with('success', 'Transfert effectué avec succès.');
    }
}