<x-app-layout>

    <x-slot name="header">

        <div class="flex justify-between items-center">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Mes comptes
            </h2>

            <a
                href="{{ route('accounts.create') }}"
                class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
            >
                + Ajouter
            </a>

        </div>

    </x-slot>


    <div class="py-6">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- En-tête --}}
            <div class="mb-8">

                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Mes comptes
                </h1>

                <p class="mt-2 text-gray-500 dark:text-gray-400">
                    Une vue rapide de ta situation financière.
                </p>

            </div>


            {{-- Message de succès --}}
            @if (session('success'))

                <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-900/20 p-4">

                    <p class="text-sm text-green-700 dark:text-green-300">
                        {{ session('success') }}
                    </p>

                </div>

            @endif


            {{-- Aucun compte --}}
            @if ($accounts->isEmpty())

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-8 text-center">

                    <div class="text-4xl mb-4">
                        💳
                    </div>

                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        Aucun compte
                    </h2>

                    <p class="mt-2 text-gray-500 dark:text-gray-400">
                        Commence par ajouter ton premier compte.
                    </p>

                    <a
                        href="{{ route('accounts.create') }}"
                        class="inline-block mt-5 px-5 py-2.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                    >
                        + Ajouter mon premier compte
                    </a>

                </div>

            @else


                {{-- Comptes --}}
                <div class="space-y-6">

                    @foreach ($accountAnalyses as $accountData)

                        @php
                            $account = $accountData['account'];

                            $realBalance = $accountData['real_balance'];

                            $expenses = $accountData['expenses'];

                            $monthlyExpenseTotal = $accountData['monthly_expense_total'];

                            $upcomingIncome = $accountData['upcoming_income'];

                            $upcomingExpenses = $accountData['upcoming_expenses'];

                            $forecastBalance = $accountData['forecast_balance'];
                        @endphp


                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">


                            {{-- ============================== --}}
                            {{-- HEADER COMPTE --}}
                            {{-- ============================== --}}

                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">

                                <div>

                                    <div class="flex items-center gap-3">

                                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                            {{ $account->name }}
                                        </h2>

                                        @if ($account->is_shared)

                                            <span class="text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                                Partagé
                                            </span>

                                        @endif

                                    </div>

                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        {{ match ($account->type) {
                                            'current' => 'Compte courant',
                                            'savings' => 'Épargne',
                                            'joint' => 'Compte joint',
                                            default => 'Autre compte',
                                        } }}
                                    </p>

                                </div>


                                <div class="flex gap-3">

                                    <a
                                        href="{{ route('accounts.transactions.index', $account) }}"
                                        class="text-sm text-blue-600 hover:underline"
                                    >
                                        Transactions →
                                    </a>

                                    <a
                                        href="{{ route('accounts.edit', $account) }}"
                                        class="text-sm text-gray-500 hover:underline"
                                    >
                                        Modifier
                                    </a>

                                </div>

                            </div>


                            {{-- ============================== --}}
                            {{-- SOLDE RÉEL --}}
                            {{-- ============================== --}}

                            <div class="mt-6">

                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Solde réel
                                </p>

                                <p class="text-4xl font-bold text-gray-900 dark:text-white mt-1">
                                    {{ number_format($realBalance, 2, ',', ' ') }} €
                                </p>

                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Argent réellement disponible aujourd'hui
                                </p>

                            </div>


                            {{-- ============================== --}}
                            {{-- ANALYSE DU MOIS --}}
                            {{-- ============================== --}}

                            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">

                                <div class="grid lg:grid-cols-2 gap-8">


                                    {{-- DONUT --}}
                                    <div>

                                        <div class="mb-5">

                                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                                Dépenses ce mois
                                            </h3>

                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                Dépenses réellement enregistrées
                                            </p>

                                        </div>


                                        @if ($monthlyExpenseTotal > 0)

                                            @php
                                                $chartColors = [
                                                    '#3B82F6',
                                                    '#8B5CF6',
                                                    '#F59E0B',
                                                    '#EF4444',
                                                    '#6B7280',
                                                ];

                                                $currentAngle = 0;

                                                $segments = [];

                                                foreach ($expenses as $index => $expense) {
                                                    $percentage = $expense['percentage'];

                                                    $start = $currentAngle;

                                                    $currentAngle += ($percentage / 100) * 360;

                                                    $end = $currentAngle;

                                                    $segments[] = [
                                                        'start' => $start,
                                                        'end' => $end,
                                                        'color' => $chartColors[$index % count($chartColors)],
                                                    ];
                                                }

                                                $gradientParts = [];

                                                foreach ($segments as $segment) {
                                                    $gradientParts[] =
                                                        $segment['color']
                                                        . ' '
                                                        . $segment['start']
                                                        . 'deg '
                                                        . $segment['end']
                                                        . 'deg';
                                                }

                                                $gradient = implode(', ', $gradientParts);
                                            @endphp


                                            <div class="flex flex-col sm:flex-row items-center gap-8">


                                                {{-- Donut --}}
                                                <div
                                                    class="relative w-44 h-44 rounded-full shrink-0"
                                                    style="background: conic-gradient({{ $gradient }});"
                                                >

                                                    <div class="absolute inset-6 bg-white dark:bg-gray-800 rounded-full flex flex-col items-center justify-center">

                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            Total
                                                        </span>

                                                        <span class="text-lg font-bold text-gray-900 dark:text-white">
                                                            {{ number_format($monthlyExpenseTotal, 2, ',', ' ') }} €
                                                        </span>

                                                    </div>

                                                </div>


                                                {{-- Légende --}}
                                                <div class="space-y-3 w-full">

                                                    @foreach ($expenses as $index => $expense)

                                                        @php
                                                            $category = $expense['category'];

                                                            $color = $chartColors[$index % count($chartColors)];
                                                        @endphp


                                                        <div class="flex items-center justify-between gap-3">

                                                            <div class="flex items-center gap-2 min-w-0">

                                                                <span
                                                                    class="w-3 h-3 rounded-full shrink-0"
                                                                    style="background-color: {{ $color }};"
                                                                ></span>

                                                                <span class="text-sm text-gray-700 dark:text-gray-300 truncate">

                                                                    @if ($expense['is_other'])

                                                                        Autres

                                                                    @else

                                                                        {{ $category->icon }}
                                                                        {{ $category->name }}

                                                                    @endif

                                                                </span>

                                                            </div>


                                                            <div class="text-right shrink-0">

                                                                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                                                    {{ number_format($expense['amount'], 2, ',', ' ') }} €
                                                                </span>

                                                                <span class="text-xs text-gray-500 ml-1">
                                                                    {{ $expense['percentage'] }} %
                                                                </span>

                                                            </div>

                                                        </div>

                                                    @endforeach

                                                </div>

                                            </div>


                                        @else

                                            <div class="h-44 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-gray-700/40">

                                                <div class="text-center">

                                                    <div class="text-3xl mb-2">
                                                        🎉
                                                    </div>

                                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                        Aucune dépense ce mois-ci
                                                    </p>

                                                    <p class="text-xs text-gray-500 mt-1">
                                                        Tes dépenses apparaîtront ici.

                                                    </p>

                                                </div>

                                            </div>

                                        @endif

                                    </div>


                                    {{-- À VENIR --}}
                                    <div>

                                        <div class="mb-5">

                                            <h3 class="font-semibold text-gray-900 dark:text-white">
                                                À venir
                                            </h3>

                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                Mouvements prévus jusqu'à la fin du mois
                                            </p>

                                        </div>


                                        <div class="space-y-4">


                                            {{-- Revenus --}}
                                            <div class="flex justify-between items-center p-4 rounded-xl bg-green-50 dark:bg-green-900/10">

                                                <div>

                                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                                        Revenus à venir
                                                    </p>

                                                    <p class="text-xs text-gray-500 mt-1">
                                                        Jusqu'à la fin du mois
                                                    </p>

                                                </div>

                                                <p class="font-bold text-green-600">
                                                    +{{ number_format($upcomingIncome, 2, ',', ' ') }} €
                                                </p>

                                            </div>


                                            {{-- Dépenses --}}
                                            <div class="flex justify-between items-center p-4 rounded-xl bg-red-50 dark:bg-red-900/10">

                                                <div>

                                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                                        Dépenses à venir
                                                    </p>

                                                    <p class="text-xs text-gray-500 mt-1">
                                                        Jusqu'à la fin du mois
                                                    </p>

                                                </div>

                                                <p class="font-bold text-red-600">
                                                    -{{ number_format($upcomingExpenses, 2, ',', ' ') }} €
                                                </p>

                                            </div>


                                            {{-- Prévision --}}
                                            <div class="p-4 rounded-xl bg-gray-50 dark:bg-gray-700/40">

                                                <div class="flex justify-between items-center">

                                                    <div>

                                                        <p class="text-sm text-gray-600 dark:text-gray-300">
                                                            Prévu fin du mois
                                                        </p>

                                                        <p class="text-xs text-gray-500 mt-1">
                                                            Solde réel + mouvements à venir
                                                        </p>

                                                    </div>

                                                    <p
                                                        class="text-xl font-bold
                                                            {{ $forecastBalance >= $realBalance
                                                                ? 'text-green-600'
                                                                : 'text-red-600' }}"
                                                    >
                                                        {{ number_format($forecastBalance, 2, ',', ' ') }} €
                                                    </p>

                                                </div>

                                            </div>


                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- ============================== --}}
                            {{-- ACTIONS --}}
                            {{-- ============================== --}}

                            <div class="mt-8 pt-5 border-t border-gray-200 dark:border-gray-700">

                                <div class="flex flex-wrap gap-3">

                                    <a
                                        href="{{ route('accounts.transactions.create', $account) }}"
                                        class="px-4 py-2 rounded-lg bg-gray-800 text-white text-sm hover:bg-gray-700"
                                    >
                                        + Transaction
                                    </a>

                                    <a
                                        href="{{ route('accounts.transactions.index', $account) }}"
                                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
                                    >
                                        Voir les transactions
                                    </a>

                                </div>

                            </div>


                        </div>

                    @endforeach

                </div>


                {{-- ============================== --}}
                {{-- ACTIONS GLOBALES --}}
                {{-- ============================== --}}

                <div class="mt-8">

                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        Actions
                    </h2>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">


                        <a
                            href="{{ route('accounts.create') }}"
                            class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                        >

                            <p class="font-semibold text-gray-900 dark:text-white">
                                💳 Nouveau compte
                            </p>

                            <p class="text-sm text-gray-500 mt-1">
                                Ajouter un compte
                            </p>

                        </a>


                        <a
                            href="{{ route('recurring-transactions.index') }}"
                            class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                        >

                            <p class="font-semibold text-gray-900 dark:text-white">
                                🔄 Récurrences
                            </p>

                            <p class="text-sm text-gray-500 mt-1">
                                Revenus et dépenses réguliers
                            </p>

                        </a>


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


            @endif

        </div>

    </div>

</x-app-layout>