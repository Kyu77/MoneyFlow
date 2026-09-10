<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Services\AccountAnalysisService;
use App\Services\FinancialForecastService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Transactions d'un compte
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        Account $account,
        AccountAnalysisService $analysisService,
        FinancialForecastService $forecastService
    ): View {
        $this->authorizeAccount($account);

        $filter = $request->query('filter', 'all');

        if (!in_array($filter, ['all', 'income', 'expense'], true)) {
            $filter = 'all';
        }

        $transactionsQuery = $account->transactions()
            ->with('category')
            ->latest('transaction_date')
            ->latest();

        if ($filter !== 'all') {
            $transactionsQuery->where('type', $filter);
        }

        $transactions = $transactionsQuery->get();

        $monthlyIncome = $analysisService
            ->monthlyIncomeTotal($account);

        $monthlyExpenses = $analysisService
            ->monthlyExpenseTotal($account);

        $monthlyBalanceChange = $monthlyIncome - $monthlyExpenses;

        $expenses = $analysisService
            ->topMonthlyExpenses($account);

        $upcomingIncome = $forecastService
            ->upcomingRecurringIncome($account);

        $upcomingExpenses = $forecastService
            ->upcomingRecurringExpenses($account);

        $forecastBalance = $forecastService
            ->forecastAccountBalance($account);

        return view('transactions.index', [
            'account' => $account,
            'transactions' => $transactions,
            'filter' => $filter,
            'monthlyIncome' => $monthlyIncome,
            'monthlyExpenses' => $monthlyExpenses,
            'monthlyBalanceChange' => $monthlyBalanceChange,
            'expenses' => $expenses,
            'upcomingIncome' => $upcomingIncome,
            'upcomingExpenses' => $upcomingExpenses,
            'forecastBalance' => $forecastBalance,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Ajouter depuis un compte
    |--------------------------------------------------------------------------
    */

    public function create(Account $account): View
    {
        $this->authorizeAccount($account);

        return $this->transactionForm($account);
    }


    /*
    |--------------------------------------------------------------------------
    | Ajouter depuis le Dashboard
    |--------------------------------------------------------------------------
    */

    public function createGlobal(): View
    {
        $accounts = auth()->user()
            ->accounts()
            ->orderBy('name')
            ->get();

        /*
        | On présélectionne le compte courant si l'utilisateur
        | en possède un.
        */
        $selectedAccount = $accounts
            ->firstWhere('type', 'current');

        /*
        | Sinon, on prend simplement le premier compte.
        */
        if (!$selectedAccount) {
            $selectedAccount = $accounts->first();
        }

        return $this->transactionForm($selectedAccount);
    }


    /*
    |--------------------------------------------------------------------------
    | Formulaire commun
    |--------------------------------------------------------------------------
    */

    private function transactionForm(?Account $selectedAccount = null): View
    {
        $user = auth()->user();

        $accounts = $user->accounts()
            ->orderBy('name')
            ->get();

        $categories = $user->categories()
            ->where('type', 'expense')
            ->orderBy('name')
            ->get();

        $incomeCategories = $user->categories()
            ->where('type', 'income')
            ->orderBy('name')
            ->get();

        return view('transactions.create', [
            'account' => $selectedAccount,
            'accounts' => $accounts,
            'categories' => $categories,
            'incomeCategories' => $incomeCategories,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Ajouter depuis un compte
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Account $account
    ): RedirectResponse {
        $this->authorizeAccount($account);

        $validated = $this->validateTransaction($request);

        /*
        | Vérification de la catégorie
        */
        $this->validateCategory(
            $validated['category_id'] ?? null,
            $validated['type']
        );

        $transaction = $this->createTransaction(
            $account,
            $validated
        );

        return redirect()
            ->route(
                'accounts.transactions.index',
                $account
            )
            ->with(
                'success',
                'Transaction ajoutée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Ajouter depuis le Dashboard
    |--------------------------------------------------------------------------
    */

    public function storeGlobal(Request $request): RedirectResponse
    {
        $validated = $this->validateTransaction(
            $request,
            true
        );

        /*
        | Le compte choisi doit obligatoirement appartenir
        | à l'utilisateur connecté.
        */
        $account = auth()->user()
            ->accounts()
            ->findOrFail($validated['account_id']);

        $this->validateCategory(
            $validated['category_id'] ?? null,
            $validated['type']
        );

        unset($validated['account_id']);

        $this->createTransaction(
            $account,
            $validated
        );

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                'Transaction ajoutée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Afficher une transaction
    |--------------------------------------------------------------------------
    */

    public function show(
        Account $account,
        Transaction $transaction
    ): View {
        $this->authorizeTransaction(
            $account,
            $transaction
        );

        $transaction->load('category');

        return view(
            'transactions.show',
            compact(
                'account',
                'transaction'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Modifier
    |--------------------------------------------------------------------------
    */

    public function edit(
        Account $account,
        Transaction $transaction
    ): View {
        $this->authorizeTransaction(
            $account,
            $transaction
        );

        $categories = auth()->user()
            ->categories()
            ->where('type', $transaction->type)
            ->orderBy('name')
            ->get();

        return view(
            'transactions.edit',
            compact(
                'account',
                'transaction',
                'categories'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Mise à jour
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Account $account,
        Transaction $transaction
    ): RedirectResponse {
        $this->authorizeTransaction(
            $account,
            $transaction
        );

        $validated = $this->validateTransaction(
            $request
        );

        DB::transaction(function () use (
            $transaction,
            $validated
        ) {

            $this->reverseBalanceImpact(
                $transaction
            );

            $this->validateCategory(
                $validated['category_id'] ?? null,
                $validated['type']
            );

            $transaction->update(
                $validated
            );

            $this->applyBalanceImpact(
                $transaction
            );
        });

        return redirect()
            ->route(
                'accounts.transactions.index',
                $account
            )
            ->with(
                'success',
                'Transaction modifiée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Suppression
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Account $account,
        Transaction $transaction
    ): RedirectResponse {
        $this->authorizeTransaction(
            $account,
            $transaction
        );

        DB::transaction(function () use (
            $transaction
        ) {

            $this->reverseBalanceImpact(
                $transaction
            );

            $transaction->delete();
        });

        return redirect()
            ->route(
                'accounts.transactions.index',
                $account
            )
            ->with(
                'success',
                'Transaction supprimée avec succès.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    private function validateTransaction(
        Request $request,
        bool $withAccount = false
    ): array {

        $rules = [
            'category_id' => [
                'nullable',
                'integer',
            ],

            'type' => [
                'required',
                'in:income,expense',
            ],

            'amount' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'description' => [
                'nullable',
                'string',
                'max:255',
            ],

            'transaction_date' => [
                'required',
                'date',
            ],
        ];

        if ($withAccount) {
            $rules['account_id'] = [
                'required',
                'integer',
            ];
        }

        return $request->validate($rules);
    }


    /*
    |--------------------------------------------------------------------------
    | Vérification catégorie
    |--------------------------------------------------------------------------
    */

    private function validateCategory(
        $categoryId,
        string $type
    ): void {

        if (!$categoryId) {
            return;
        }

        auth()->user()
            ->categories()
            ->where('type', $type)
            ->findOrFail($categoryId);
    }


    /*
    |--------------------------------------------------------------------------
    | Création réelle
    |--------------------------------------------------------------------------
    */

    private function createTransaction(
        Account $account,
        array $validated
    ): Transaction {

        return DB::transaction(
            function () use (
                $account,
                $validated
            ) {

                $transaction = $account
                    ->transactions()
                    ->create($validated);

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

                return $transaction;
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Impact solde
    |--------------------------------------------------------------------------
    */

    private function applyBalanceImpact(
        Transaction $transaction
    ): void {

        if ($transaction->type === 'income') {

            $transaction->account->increment(
                'balance',
                $transaction->amount
            );

        } else {

            $transaction->account->decrement(
                'balance',
                $transaction->amount
            );
        }
    }


    private function reverseBalanceImpact(
        Transaction $transaction
    ): void {

        if ($transaction->type === 'income') {

            $transaction->account->decrement(
                'balance',
                $transaction->amount
            );

        } else {

            $transaction->account->increment(
                'balance',
                $transaction->amount
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Sécurité
    |--------------------------------------------------------------------------
    */

    private function authorizeAccount(
        Account $account
    ): void {

        abort_unless(
            $account->user_id === auth()->id(),
            404
        );
    }


    private function authorizeTransaction(
        Account $account,
        Transaction $transaction
    ): void {

        $this->authorizeAccount(
            $account
        );

        abort_unless(
            $transaction->account_id === $account->id,
            404
        );
    }
}