<x-app-layout>

    {{-- ========================================================= --}}
    {{-- HEADER DESKTOP                                           --}}
    {{-- ========================================================= --}}

    <x-slot name="header">

        <div class="hidden sm:flex items-center justify-between">

            <div>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Ton espace financier
                </p>

                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                    Dashboard
                </h2>

            </div>


            <a
                href="{{ route('transactions.create') }}"
                class="mf-button-primary"
            >
                <span class="text-lg">
                    +
                </span>

                Ajouter une transaction
            </a>

        </div>

    </x-slot>


    {{-- ========================================================= --}}
    {{-- CONTENU                                                   --}}
    {{-- ========================================================= --}}

    <div class="min-h-screen bg-gray-50 dark:bg-gray-950">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-8">


            {{-- ================================================= --}}
            {{-- HEADER MOBILE                                      --}}
            {{-- ================================================= --}}

            <div class="sm:hidden mb-6 mf-fade-in">

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Bonjour {{ auth()->user()->name }} 👋
                </p>

                <div class="flex items-center justify-between mt-1">

                    <h1 class="text-2xl font-bold tracking-tight text-gray-950 dark:text-white">
                        Tes finances
                    </h1>


                    <a
                        href="{{ route('transactions.create') }}"
                        class="w-11 h-11 rounded-2xl bg-gray-950 dark:bg-white text-white dark:text-gray-950 flex items-center justify-center shadow-lg active:scale-95 transition"
                        aria-label="Ajouter une transaction"
                    >
                        <span class="text-2xl font-light">
                            +
                        </span>
                    </a>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- SOLDE DISPONIBLE                                   --}}
            {{-- ================================================= --}}

            <section class="mf-fade-in">

                <div class="relative overflow-hidden rounded-[2rem] bg-gray-950 dark:bg-white text-white dark:text-gray-950 p-6 sm:p-8 shadow-xl">


                    {{-- Décoration légère --}}

                    <div
                        class="absolute -right-16 -top-16 w-48 h-48 rounded-full bg-indigo-500/20 blur-3xl"
                    ></div>

                    <div
                        class="absolute -left-20 -bottom-20 w-48 h-48 rounded-full bg-indigo-500/10 blur-3xl"
                    ></div>


                    <div class="relative">


                        {{-- Titre --}}

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm text-gray-400 dark:text-gray-500">
                                    Solde disponible maintenant
                                </p>

                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Argent réellement disponible
                                </p>

                            </div>


                            <div class="w-10 h-10 rounded-xl bg-white/10 dark:bg-gray-950/10 flex items-center justify-center">
                                💳
                            </div>

                        </div>


                        {{-- Montant --}}

                        <div class="mt-5">

                            <p class="text-4xl sm:text-5xl font-bold tracking-tight">

                                {{ number_format(
                                    $totalRealBalance,
                                    2,
                                    ',',
                                    ' '
                                ) }}

                                <span class="text-2xl sm:text-3xl font-medium opacity-50">
                                    €
                                </span>

                            </p>

                        </div>


                        {{-- À venir --}}

                        <div class="mt-7 grid grid-cols-2 gap-3">


                            {{-- Revenus à venir --}}

                            <div class="rounded-2xl bg-white/10 dark:bg-gray-950/10 p-4">

                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                    Revenus à venir
                                </p>

                                <p class="text-lg font-bold text-green-400 dark:text-green-600 mt-1">

                                    +{{ number_format(
                                        $totalUpcomingIncome,
                                        2,
                                        ',',
                                        ' '
                                    ) }}

                                    €

                                </p>

                            </div>


                            {{-- Dépenses à venir --}}

                            <div class="rounded-2xl bg-white/10 dark:bg-gray-950/10 p-4">

                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                    Dépenses à venir
                                </p>

                                <p class="text-lg font-bold text-red-400 dark:text-red-600 mt-1">

                                    -{{ number_format(
                                        $totalUpcomingExpenses,
                                        2,
                                        ',',
                                        ' '
                                    ) }}

                                    €

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </section>


            {{-- ================================================= --}}
            {{-- PRÉVISION FIN DU MOIS                              --}}
            {{-- ================================================= --}}

            <section class="mt-4 mf-fade-in">

                <div class="mf-card p-5 sm:p-6">


                    <div class="flex items-start gap-3">

                        <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center shrink-0">
                            📅
                        </div>


                        <div>

                            <h2 class="font-bold text-gray-900 dark:text-white">
                                Prévision fin du mois
                            </h2>

                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Estimation basée sur les mouvements récurrents à venir.
                            </p>

                        </div>

                    </div>


                    @php
                        $forecastDifference =
                            $totalForecastBalance - $totalRealBalance;
                    @endphp


                    <div class="mt-5 flex items-end justify-between gap-4">


                        <div>

                            <p class="text-3xl font-bold tracking-tight text-gray-950 dark:text-white">

                                {{ number_format(
                                    $totalForecastBalance,
                                    2,
                                    ',',
                                    ' '
                                ) }}

                                €

                            </p>

                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Solde estimé
                            </p>

                        </div>


                        <span
                            class="mf-badge
                                {{ $forecastDifference >= 0
                                    ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300'
                                    : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' }}"
                        >

                            {{ $forecastDifference >= 0 ? '+' : '' }}

                            {{ number_format(
                                $forecastDifference,
                                2,
                                ',',
                                ' '
                            ) }}

                            €

                        </span>

                    </div>

                </div>

            </section>


            {{-- ================================================= --}}
            {{-- ACTIVITÉ DU MOIS                                   --}}
            {{-- ================================================= --}}

            <section class="mt-8">


                <div class="mb-4">

                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                        Activité
                    </p>

                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-1">
                        Ce mois
                    </h2>

                </div>


                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">


                    {{-- Revenus réels --}}

                    <div class="mf-card p-4 sm:p-5">

                        <div class="flex items-center justify-between">

                            <span class="w-9 h-9 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center text-lg">
                                ↗
                            </span>

                            <span class="text-xs font-medium text-gray-400">
                                Revenus
                            </span>

                        </div>


                        <p class="text-xl sm:text-2xl font-bold text-gray-950 dark:text-white mt-4">

                            +{{ number_format(
                                $totalMonthlyIncome,
                                2,
                                ',',
                                ' '
                            ) }}

                            €

                        </p>


                        <p class="text-xs text-gray-400 mt-1">
                            Réellement encaissés
                        </p>

                    </div>


                    {{-- Dépenses réelles --}}

                    <div class="mf-card p-4 sm:p-5">

                        <div class="flex items-center justify-between">

                            <span class="w-9 h-9 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-lg">
                                ↘
                            </span>

                            <span class="text-xs font-medium text-gray-400">
                                Dépenses
                            </span>

                        </div>


                        <p class="text-xl sm:text-2xl font-bold text-gray-950 dark:text-white mt-4">

                            -{{ number_format(
                                $totalMonthlyExpenses,
                                2,
                                ',',
                                ' '
                            ) }}

                            €

                        </p>


                        <p class="text-xs text-gray-400 mt-1">
                            Réellement dépensés
                        </p>

                    </div>


                    {{-- Net du mois --}}

                    @php
                        $monthlyNet =
                            $totalMonthlyIncome
                            - $totalMonthlyExpenses;
                    @endphp


                    <div class="mf-card p-4 sm:p-5 col-span-2 sm:col-span-1">

                        <div class="flex items-center justify-between">

                            <span class="w-9 h-9 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-lg">
                                ✦
                            </span>

                            <span class="text-xs font-medium text-gray-400">
                                Variation
                            </span>

                        </div>


                        <p
                            class="text-xl sm:text-2xl font-bold mt-4
                                {{ $monthlyNet >= 0
                                    ? 'text-green-600 dark:text-green-400'
                                    : 'text-red-600 dark:text-red-400' }}"
                        >

                            {{ $monthlyNet >= 0 ? '+' : '' }}

                            {{ number_format(
                                $monthlyNet,
                                2,
                                ',',
                                ' '
                            ) }}

                            €

                        </p>


                        <p class="text-xs text-gray-400 mt-1">
                            Revenus − dépenses
                        </p>

                    </div>

                </div>

            </section>


            {{-- ================================================= --}}
            {{-- COMPTES                                             --}}
            {{-- ================================================= --}}

            <section class="mt-8">


                <div class="flex items-end justify-between mb-4">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                            Ton argent
                        </p>

                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-1">
                            Mes comptes
                        </h2>

                    </div>


                    <a
                        href="{{ route('accounts.index') }}"
                        class="text-sm font-semibold text-indigo-600 dark:text-indigo-400"
                    >
                        Voir tout
                    </a>

                </div>


                @if ($accountForecasts->isEmpty())

                    <div class="mf-card p-7 text-center">

                        <div class="w-14 h-14 mx-auto rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-2xl">
                            💳
                        </div>


                        <h3 class="font-bold text-gray-900 dark:text-white mt-4">
                            Aucun compte
                        </h3>


                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Commence par ajouter ton premier compte.
                        </p>


                        <a
                            href="{{ route('accounts.create') }}"
                            class="mf-button-primary mt-5"
                        >
                            Ajouter un compte
                        </a>

                    </div>

                @else

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">

                        @foreach ($accountForecasts as $data)

                            @php

                                $account = $data['account'];

                                $accountTypeLabel = match ($account->type) {

                                    'current' => 'Compte courant',

                                    'savings' => 'Épargne',

                                    'joint' => 'Compte joint',

                                    default => 'Autre compte',

                                };

                            @endphp


                            <a
                                href="{{ route('accounts.show', $account) }}"
                                class="mf-card mf-card-hover p-5 block"
                            >


                                <div class="flex items-start justify-between">

                                    <div class="flex items-center gap-3">

                                        <div class="w-11 h-11 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                            💳
                                        </div>


                                        <div>

                                            <p class="font-semibold text-gray-900 dark:text-white">
                                                {{ $account->name }}
                                            </p>

                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                {{ $accountTypeLabel }}
                                            </p>

                                        </div>

                                    </div>


                                    <span class="text-gray-300 dark:text-gray-600">
                                        →
                                    </span>

                                </div>


                                <div class="mt-6">

                                    <p class="text-2xl font-bold text-gray-950 dark:text-white">

                                        {{ number_format(
                                            $data['real_balance'],
                                            2,
                                            ',',
                                            ' '
                                        ) }}

                                        €

                                    </p>


                                    <p class="text-xs text-gray-400 mt-1">
                                        Disponible maintenant
                                    </p>

                                </div>


                                <div class="mt-5 pt-4 border-t border-gray-100 dark:border-gray-700">

                                    <div class="flex items-center justify-between text-xs">

                                        <span class="text-gray-500 dark:text-gray-400">
                                            Prévu fin du mois
                                        </span>


                                        <span class="font-semibold text-gray-900 dark:text-white">

                                            {{ number_format(
                                                $data['forecast_balance'],
                                                2,
                                                ',',
                                                ' '
                                            ) }}

                                            €

                                        </span>

                                    </div>

                                </div>

                            </a>

                        @endforeach

                    </div>

                @endif

            </section>


            {{-- ================================================= --}}
            {{-- OBJECTIFS                                           --}}
            {{-- ================================================= --}}

            <section class="mt-8">


                <div class="flex items-end justify-between mb-4">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">
                            Projets
                        </p>

                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-1">
                            Mes objectifs
                        </h2>

                    </div>


                    <a
                        href="{{ route('savings-goals.index') }}"
                        class="text-sm font-semibold text-indigo-600 dark:text-indigo-400"
                    >
                        Voir tout
                    </a>

                </div>


                @if ($savingsGoals->isEmpty())

                    <div class="mf-card p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">


                        <div class="flex items-center gap-4">

                            <div class="w-12 h-12 rounded-2xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-xl">
                                🎯
                            </div>


                            <div>

                                <h3 class="font-bold text-gray-900 dark:text-white">
                                    Aucun objectif pour le moment
                                </h3>

                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Donne-toi un objectif et suis sa progression.
                                </p>

                            </div>

                        </div>


                        <a
                            href="{{ route('savings-goals.create') }}"
                            class="mf-button-primary shrink-0"
                        >
                            Créer un objectif
                        </a>

                    </div>

                @else

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">

                        @foreach ($savingsGoals as $data)

                            @php

                                $percentage = $data['percentage'];

                                $current = $data['current_amount'];

                                $target = $data['target_amount'];

                            @endphp


                            <a
                                href="{{ route('savings-goals.edit', $data['goal']) }}"
                                class="mf-card mf-card-hover p-5 block"
                            >


                                <div class="flex items-center justify-between gap-3">


                                    <div class="flex items-center gap-3 min-w-0">

                                        <div class="w-11 h-11 rounded-2xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center shrink-0">
                                            🎯
                                        </div>


                                        <div class="min-w-0">

                                            <p class="font-semibold text-gray-900 dark:text-white truncate">
                                                {{ $data['goal']->name }}
                                            </p>


                                            @if ($data['goal']->target_date)

                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">

                                                    Objectif :
                                                    {{ $data['goal']->target_date->format('d/m/Y') }}

                                                </p>

                                            @else

                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                    Sans date limite
                                                </p>

                                            @endif

                                        </div>

                                    </div>


                                    <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ $percentage }}%
                                    </span>

                                </div>


                                <div class="mt-5">


                                    <div class="flex justify-between text-xs mb-2">

                                        <span class="text-gray-500 dark:text-gray-400">

                                            {{ number_format(
                                                $current,
                                                0,
                                                ',',
                                                ' '
                                            ) }}

                                            €

                                        </span>


                                        <span class="text-gray-400">

                                            {{ number_format(
                                                $target,
                                                0,
                                                ',',
                                                ' '
                                            ) }}

                                            €

                                        </span>

                                    </div>


                                    <div class="h-2 rounded-full bg-gray-100 dark:bg-gray-700 overflow-hidden">

                                        <div
                                            class="h-full rounded-full bg-indigo-600 transition-all duration-500"
                                            style="width: {{ $percentage }}%"
                                        ></div>

                                    </div>

                                </div>

                            </a>

                        @endforeach

                    </div>

                @endif

            </section>


            {{-- ================================================= --}}
            {{-- ACTIONS RAPIDES                                    --}}
            {{-- ================================================= --}}

            <section class="mt-8 pb-4">


                <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-3">
                    Actions rapides
                </p>


                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">


                    {{-- Transaction --}}

                    <a
                        href="{{ route('transactions.create') }}"
                        class="mf-card mf-card-hover p-4 text-center"
                    >

                        <div class="w-10 h-10 mx-auto rounded-xl bg-gray-950 dark:bg-white text-white dark:text-gray-950 flex items-center justify-center text-xl">
                            +
                        </div>


                        <p class="font-semibold text-sm text-gray-900 dark:text-white mt-3">
                            Transaction
                        </p>

                    </a>


                    {{-- Virement --}}

                    <a
                        href="{{ route('transfers.create') }}"
                        class="mf-card mf-card-hover p-4 text-center"
                    >

                        <div class="w-10 h-10 mx-auto rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-lg">
                            ↔
                        </div>


                        <p class="font-semibold text-sm text-gray-900 dark:text-white mt-3">
                            Virement
                        </p>

                    </a>


                    {{-- Objectif --}}

                    <a
                        href="{{ route('savings-goals.create') }}"
                        class="mf-card mf-card-hover p-4 text-center"
                    >

                        <div class="w-10 h-10 mx-auto rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-lg">
                            🎯
                        </div>


                        <p class="font-semibold text-sm text-gray-900 dark:text-white mt-3">
                            Objectif
                        </p>

                    </a>


                    {{-- Récurrence --}}

                    <a
                        href="{{ route('recurring-transactions.create') }}"
                        class="mf-card mf-card-hover p-4 text-center"
                    >

                        <div class="w-10 h-10 mx-auto rounded-xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-lg">
                            🔄
                        </div>


                        <p class="font-semibold text-sm text-gray-900 dark:text-white mt-3">
                            Récurrence
                        </p>

                    </a>

                </div>

            </section>


        </div>

    </div>

</x-app-layout>

