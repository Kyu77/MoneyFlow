<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            MoneyFlow
        </h2>

    </x-slot>


    <div class="py-6">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- ===================================================== --}}
            {{-- BIENVENUE --}}
            {{-- ===================================================== --}}

            <div class="mb-8">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                <div>

                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                         Bonjour {{ auth()->user()->name }} 👋
                    </h1>

                     <p class="mt-2 text-gray-500 dark:text-gray-400">
                         Voici l'essentiel de tes finances.
                    </p>

             </div>


        {{-- Ajouter une transaction --}}
        <a
            href="{{ route('transactions.create') }}"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 min-h-[54px] px-6 rounded-2xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold shadow-lg hover:bg-gray-800 dark:hover:bg-gray-100 active:scale-[0.98] transition"
        >

            <span class="text-xl">
                +
            </span>

            <span>
                Ajouter une transaction
            </span>

        </a>

    </div>

            </div>


            {{-- ===================================================== --}}
            {{-- VUE GLOBALE --}}
            {{-- ===================================================== --}}

            @if ($accounts->isNotEmpty())

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">


                    {{-- Solde réel --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Solde réel
                        </p>

                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                            {{ number_format($totalRealBalance, 2, ',', ' ') }} €
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            Sur tous tes comptes
                        </p>

                    </div>


                    {{-- Dépenses --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Dépenses ce mois
                        </p>

                        <p class="text-2xl font-bold text-red-600 mt-2">
                            -{{ number_format($totalMonthlyExpenses, 2, ',', ' ') }} €
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            Dépenses réellement passées
                        </p>

                    </div>


                    {{-- À venir --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            À venir
                        </p>

                        <div class="mt-2 space-y-1">

                            <p class="text-sm font-semibold text-green-600">
                                +{{ number_format($totalUpcomingIncome, 2, ',', ' ') }} €
                            </p>

                            <p class="text-sm font-semibold text-red-600">
                                -{{ number_format($totalUpcomingExpenses, 2, ',', ' ') }} €
                            </p>

                        </div>

                        <p class="text-xs text-gray-500 mt-1">
                            Mouvements prévus
                        </p>

                    </div>


                    {{-- Prévision --}}
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Prévu fin du mois
                        </p>

                        <p
                            class="text-2xl font-bold mt-2
                                {{ $totalForecastBalance >= $totalRealBalance
                                    ? 'text-green-600'
                                    : 'text-red-600' }}"
                        >
                            {{ number_format($totalForecastBalance, 2, ',', ' ') }} €
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            Avec les mouvements à venir
                        </p>

                    </div>


                </div>

            @endif


            {{-- ===================================================== --}}
            {{-- COMPTES --}}
            {{-- ===================================================== --}}

            <div class="mb-8">

                <div class="flex justify-between items-center mb-4">

                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        Mes comptes
                    </h2>

                    <a
                        href="{{ route('accounts.index') }}"
                        class="text-sm text-blue-600 hover:underline"
                    >
                        Voir le détail →
                    </a>

                </div>


                @if ($accounts->isEmpty())

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

                        <p class="text-gray-600 dark:text-gray-300">
                            Tu n'as encore aucun compte.
                        </p>

                        <a
                            href="{{ route('accounts.create') }}"
                            class="inline-block mt-4 px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                        >
                            + Ajouter mon premier compte
                        </a>

                    </div>

                @else

                    <div class="grid gap-4 md:grid-cols-2">

                        @foreach ($accountForecasts as $accountData)

                            @php
                                $account = $accountData['account'];

                                $realBalance = $accountData['real_balance'];

                                $forecast = $accountData['forecast_balance'];

                                $upcomingIncome = $accountData['upcoming_income'];

                                $upcomingExpenses = $accountData['upcoming_expenses'];

                                $difference = $forecast - $realBalance;
                            @endphp


                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">


                                {{-- Header --}}
                                <div class="flex justify-between items-start">

                                    <div>

                                        <div class="flex items-center gap-2">

                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ $account->name }}
                                            </h3>

                                            @if ($account->is_shared)

                                                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                                    Partagé
                                                </span>

                                            @endif

                                        </div>

                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ match ($account->type) {
                                                'current' => 'Compte courant',
                                                'savings' => 'Épargne',
                                                'joint' => 'Compte joint',
                                                default => 'Autre compte',
                                            } }}
                                        </p>

                                    </div>


                                    <a
                                        href="{{ route('accounts.transactions.index', $account) }}"
                                        class="text-sm text-blue-600 hover:underline"
                                    >
                                        Détails →
                                    </a>

                                </div>


                                {{-- Solde --}}
                                <div class="mt-5">

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Solde réel
                                    </p>

                                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">
                                        {{ number_format($realBalance, 2, ',', ' ') }} €
                                    </p>

                                </div>


                                {{-- À venir --}}
                                <div class="mt-5 pt-5 border-t border-gray-200 dark:border-gray-700">

                                    <div class="flex justify-between items-center">

                                        <div>

                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                À venir
                                            </p>

                                            <div class="flex gap-3 mt-1">

                                                @if ($upcomingIncome > 0)

                                                    <span class="text-sm font-semibold text-green-600">
                                                        +{{ number_format($upcomingIncome, 2, ',', ' ') }} €
                                                    </span>

                                                @endif

                                                @if ($upcomingExpenses > 0)

                                                    <span class="text-sm font-semibold text-red-600">
                                                        -{{ number_format($upcomingExpenses, 2, ',', ' ') }} €
                                                    </span>

                                                @endif

                                                @if ($upcomingIncome == 0 && $upcomingExpenses == 0)

                                                    <span class="text-sm text-gray-500">
                                                        Aucun mouvement
                                                    </span>

                                                @endif

                                            </div>

                                        </div>


                                        <div class="text-right">

                                            <p class="text-xs text-gray-500">
                                                Fin du mois
                                            </p>

                                            <p
                                                class="text-lg font-bold
                                                    {{ $forecast >= $realBalance
                                                        ? 'text-green-600'
                                                        : 'text-red-600' }}"
                                            >
                                                {{ number_format($forecast, 2, ',', ' ') }} €
                                            </p>

                                        </div>

                                    </div>


                                    @if ($difference != 0)

                                        <p class="text-xs text-gray-500 mt-3">

                                            @if ($difference > 0)

                                                +{{ number_format($difference, 2, ',', ' ') }} €
                                                de variation prévue

                                            @else

                                                {{ number_format(abs($difference), 2, ',', ' ') }} €
                                                de variation prévue

                                            @endif

                                        </p>

                                    @endif

                                </div>


                            </div>

                        @endforeach

                    </div>

                @endif

            </div>


            {{-- ===================================================== --}}
            {{-- OBJECTIFS D'ÉPARGNE --}}
            {{-- ===================================================== --}}

            <div class="mb-8">

                <div class="flex justify-between items-center mb-4">

                    <div>

                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            Mes objectifs 🎯
                        </h2>

                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Suis rapidement ta progression.
                        </p>

                    </div>


                    <a
                        href="{{ route('savings-goals.index') }}"
                        class="text-sm text-blue-600 hover:underline"
                    >
                        Voir tous les objectifs →
                    </a>

                </div>


                @if ($savingsGoals->isEmpty())

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                            <div>

                                <p class="font-medium text-gray-900 dark:text-white">
                                    Aucun objectif en cours
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    Crée un objectif pour suivre ton épargne.
                                </p>

                            </div>


                            <a
                                href="{{ route('savings-goals.create') }}"
                                class="inline-block px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 text-sm"
                            >
                                + Créer un objectif
                            </a>

                        </div>

                    </div>

                @else

                    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">


                        @foreach ($savingsGoals as $goalData)

                            @php
                                $goal = $goalData['goal'];

                                $percentage = $goalData['percentage'];

                                $currentAmount = $goalData['current_amount'];

                                $targetAmount = $goalData['target_amount'];
                            @endphp


                            <a
                                href="{{ route('savings-goals.edit', $goal) }}"
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 hover:shadow-md transition"
                            >

                                <div class="flex items-center gap-5">


                                    {{-- Donut --}}
                                    <div
                                        class="relative w-28 h-28 rounded-full shrink-0"
                                        style="
                                            background:
                                                conic-gradient(
                                                    #22c55e {{ $percentage }}%,
                                                    #e5e7eb {{ $percentage }}% 100%
                                                );
                                        "
                                    >

                                        <div class="absolute inset-3 bg-white dark:bg-gray-800 rounded-full flex flex-col items-center justify-center">

                                            <span class="text-xl font-bold text-gray-900 dark:text-white">
                                                {{ $percentage }}%
                                            </span>

                                            <span class="text-[10px] text-gray-500">
                                                atteint
                                            </span>

                                        </div>

                                    </div>


                                    {{-- Infos --}}
                                    <div class="min-w-0">

                                        <h3 class="font-semibold text-gray-900 dark:text-white truncate">
                                            {{ $goal->name }}
                                        </h3>


                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">

                                            {{ number_format($currentAmount, 2, ',', ' ') }}
                                            €
                                            /
                                            {{ number_format($targetAmount, 2, ',', ' ') }}
                                            €

                                        </p>


                                        @if ($goal->target_date)

                                            <p class="text-xs text-gray-500 mt-2">

                                                Objectif :
                                                {{ $goal->target_date->format('d/m/Y') }}

                                            </p>

                                        @endif


                                    </div>


                                </div>


                                {{-- Barre de progression --}}
                                <div class="mt-5">

                                    <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">

                                        <div
                                            class="h-full bg-green-500 rounded-full"
                                            style="width: {{ $percentage }}%;"
                                        ></div>

                                    </div>

                                </div>


                            </a>

                        @endforeach

                    </div>

                @endif

            </div>


            {{-- ===================================================== --}}
            {{-- ACTIONS RAPIDES --}}
            {{-- ===================================================== --}}

            <div>

                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                    Actions rapides
                </h2>


                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">


                    {{-- Comptes --}}
                    <a
                        href="{{ route('accounts.index') }}"
                        class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                    >

                        <p class="font-semibold text-gray-900 dark:text-white">
                            💳 Comptes
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Voir mes comptes
                        </p>

                    </a>


                    {{-- Récurrences --}}
                    <a
                        href="{{ route('recurring-transactions.index') }}"
                        class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                    >

                        <p class="font-semibold text-gray-900 dark:text-white">
                            🔄 Récurrences
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Revenus et dépenses
                        </p>

                    </a>


                    {{-- Objectifs --}}
                    <a
                        href="{{ route('savings-goals.index') }}"
                        class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                    >

                        <p class="font-semibold text-gray-900 dark:text-white">
                            🎯 Objectifs
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Suivre mon épargne
                        </p>

                    </a>


                    {{-- Catégories --}}
                    <a
                        href="{{ route('categories.index') }}"
                        class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                    >

                        <p class="font-semibold text-gray-900 dark:text-white">
                            🏷️ Catégories
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Personnaliser mes catégories
                        </p>

                    </a>


                    {{-- Transfert --}}
                    <a
                        href="{{ route('transfers.create') }}"
                        class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                    >

                        <p class="font-semibold text-gray-900 dark:text-white">
                            ↔️ Transfert
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Déplacer de l'argent
                        </p>

                    </a>


                </div>

            </div>


        </div>

    </div>

</x-app-layout>