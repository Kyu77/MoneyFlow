<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

            <div>

                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $account->name }}
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Détail du compte
                </p>

            </div>

            <a
                href="{{ route('accounts.index') }}"
                class="text-sm text-blue-600 hover:underline"
            >
                ← Retour à mes comptes
            </a>

        </div>

    </x-slot>


    <div class="py-6">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- ===================================================== --}}
            {{-- MESSAGE --}}
            {{-- ===================================================== --}}

            @if (session('success'))

                <div class="mb-6 rounded-lg bg-green-50 dark:bg-green-900/20 p-4">

                    <p class="text-sm text-green-700 dark:text-green-300">
                        {{ session('success') }}
                    </p>

                </div>

            @endif


            {{-- ===================================================== --}}
            {{-- EN-TÊTE DU COMPTE --}}
            {{-- ===================================================== --}}

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 mb-6">

                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                    <div>

                        <div class="flex items-center gap-3">

                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $account->name }}
                            </h1>

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


                    <div class="flex flex-wrap gap-3">

                        <a
                            href="{{ route('accounts.transactions.create', $account) }}"
                            class="px-4 py-2 rounded-lg bg-gray-800 text-white text-sm hover:bg-gray-700"
                        >
                            + Transaction
                        </a>

                        <a
                            href="{{ route('accounts.edit', $account) }}"
                            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
                        >
                            Modifier le compte
                        </a>

                    </div>

                </div>


                {{-- Solde --}}
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Solde réel
                    </p>

                    <p class="text-4xl font-bold text-gray-900 dark:text-white mt-1">
                        {{ number_format($account->balance, 2, ',', ' ') }} €
                    </p>

                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Argent réellement disponible aujourd'hui
                    </p>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- INDICATEURS DU MOIS --}}
            {{-- ===================================================== --}}

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">


                {{-- Revenus --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Revenus ce mois
                    </p>

                    <p class="text-2xl font-bold text-green-600 mt-2">
                        +{{ number_format($monthlyIncome, 2, ',', ' ') }} €
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        Revenus réellement reçus
                    </p>

                </div>


                {{-- Dépenses --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Dépenses ce mois
                    </p>

                    <p class="text-2xl font-bold text-red-600 mt-2">
                        -{{ number_format($monthlyExpenses, 2, ',', ' ') }} €
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        Dépenses réellement passées
                    </p>

                </div>


                {{-- Variation --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5">

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Évolution ce mois
                    </p>

                    <p
                        class="text-2xl font-bold mt-2
                            {{ $monthlyBalanceChange >= 0
                                ? 'text-green-600'
                                : 'text-red-600' }}"
                    >
                        {{ $monthlyBalanceChange >= 0 ? '+' : '' }}
                        {{ number_format($monthlyBalanceChange, 2, ',', ' ') }} €
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        Revenus − dépenses
                    </p>

                </div>


            </div>


            {{-- ===================================================== --}}
            {{-- ANALYSE --}}
            {{-- ===================================================== --}}

            <div class="grid lg:grid-cols-2 gap-6 mb-6">


                {{-- ================================================= --}}
                {{-- DONUT --}}
                {{-- ================================================= --}}

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">

                    <div class="mb-6">

                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            Où part ton argent ?
                        </h2>

                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Répartition des dépenses réellement passées ce mois
                        </p>

                    </div>


                    @if ($monthlyExpenses > 0)

                        @php

                            $chartColors = [
                                '#3B82F6',
                                '#8B5CF6',
                                '#F59E0B',
                                '#EF4444',
                                '#6B7280',
                            ];

                            $currentAngle = 0;

                            $gradientParts = [];

                            foreach ($expenses as $index => $expense) {

                                $start = $currentAngle;

                                $currentAngle +=
                                    ($expense['percentage'] / 100) * 360;

                                $end = $currentAngle;

                                $gradientParts[] =
                                    $chartColors[
                                        $index % count($chartColors)
                                    ]
                                    . ' '
                                    . $start
                                    . 'deg '
                                    . $end
                                    . 'deg';
                            }

                            $gradient = implode(
                                ', ',
                                $gradientParts
                            );

                        @endphp


                        <div class="flex flex-col sm:flex-row items-center gap-8">


                            {{-- Donut --}}
                            <div
                                class="relative w-52 h-52 rounded-full shrink-0"
                                style="background: conic-gradient({{ $gradient }});"
                            >

                                <div class="absolute inset-7 bg-white dark:bg-gray-800 rounded-full flex flex-col items-center justify-center">

                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        Dépenses
                                    </span>

                                    <span class="text-xl font-bold text-gray-900 dark:text-white">
                                        {{ number_format($monthlyExpenses, 2, ',', ' ') }} €
                                    </span>

                                    <span class="text-xs text-gray-400 mt-1">
                                        ce mois
                                    </span>

                                </div>

                            </div>


                            {{-- Légende --}}
                            <div class="space-y-4 w-full">

                                @foreach ($expenses as $index => $expense)

                                    @php

                                        $category = $expense['category'];

                                        $color =
                                            $chartColors[
                                                $index % count($chartColors)
                                            ];

                                    @endphp

                                    <div class="flex items-center justify-between gap-3">

                                        <div class="flex items-center gap-3 min-w-0">

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

                        <div class="h-52 flex items-center justify-center rounded-xl bg-gray-50 dark:bg-gray-700/40">

                            <div class="text-center">

                                <div class="text-4xl mb-3">
                                    🎉
                                </div>

                                <p class="font-medium text-gray-700 dark:text-gray-300">
                                    Aucune dépense ce mois-ci
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    Rien à analyser pour le moment.
                                </p>

                            </div>

                        </div>

                    @endif

                </div>


                {{-- ================================================= --}}
                {{-- À VENIR --}}
                {{-- ================================================= --}}

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6">

                    <div class="mb-6">

                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            À venir
                        </h2>

                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Mouvements prévus jusqu'à la fin du mois
                        </p>

                    </div>


                    <div class="space-y-4">


                        {{-- Revenus --}}
                        <div class="flex items-center justify-between p-4 rounded-xl bg-green-50 dark:bg-green-900/10">

                            <div>

                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Revenus à venir
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    Récurrences prévues
                                </p>

                            </div>

                            <p class="text-lg font-bold text-green-600">
                                +{{ number_format($upcomingIncome, 2, ',', ' ') }} €
                            </p>

                        </div>


                        {{-- Dépenses --}}
                        <div class="flex items-center justify-between p-4 rounded-xl bg-red-50 dark:bg-red-900/10">

                            <div>

                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Dépenses à venir
                                </p>

                                <p class="text-xs text-gray-500 mt-1">
                                    Récurrences prévues
                                </p>

                            </div>

                            <p class="text-lg font-bold text-red-600">
                                -{{ number_format($upcomingExpenses, 2, ',', ' ') }} €
                            </p>

                        </div>


                        {{-- Prévision --}}
                        <div class="p-5 rounded-xl bg-gray-50 dark:bg-gray-700/40">

                            <div class="flex items-center justify-between gap-4">

                                <div>

                                    <p class="font-medium text-gray-700 dark:text-gray-300">
                                        Prévu fin du mois
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        Solde réel + mouvements à venir
                                    </p>

                                </div>

                                <p
                                    class="text-2xl font-bold
                                        {{ $forecastBalance >= $account->balance
                                            ? 'text-green-600'
                                            : 'text-red-600' }}"
                                >
                                    {{ number_format($forecastBalance, 2, ',', ' ') }} €
                                </p>

                            </div>

                        </div>


                        {{-- Explication --}}
                        <div class="pt-3">

                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                Le solde réel ne change pas tant qu'un mouvement
                                n'est pas réellement passé. Les mouvements futurs
                                sont uniquement pris en compte dans la prévision.
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- HISTORIQUE --}}
            {{-- ===================================================== --}}

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow">


                {{-- Header historique --}}
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">

                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                        <div>

                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                Toutes les transactions
                            </h2>

                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                Historique complet de {{ $account->name }}
                            </p>

                        </div>


                        {{-- Filtres --}}
                        <div class="flex gap-2">

                            <a
                                href="{{ route('accounts.transactions.index', $account) }}"
                                class="px-3 py-2 rounded-lg text-sm
                                    {{ $filter === 'all'
                                        ? 'bg-gray-800 text-white'
                                        : 'border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300' }}"
                            >
                                Toutes
                            </a>

                            <a
                                href="{{ route('accounts.transactions.index', ['account' => $account, 'filter' => 'income']) }}"
                                class="px-3 py-2 rounded-lg text-sm
                                    {{ $filter === 'income'
                                        ? 'bg-green-600 text-white'
                                        : 'border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300' }}"
                            >
                                Revenus
                            </a>

                            <a
                                href="{{ route('accounts.transactions.index', ['account' => $account, 'filter' => 'expense']) }}"
                                class="px-3 py-2 rounded-lg text-sm
                                    {{ $filter === 'expense'
                                        ? 'bg-red-600 text-white'
                                        : 'border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300' }}"
                            >
                                Dépenses
                            </a>

                        </div>

                    </div>

                </div>


                {{-- Transactions --}}
                @if ($transactions->isEmpty())

                    <div class="p-10 text-center">

                        <div class="text-4xl mb-3">
                            📭
                        </div>

                        <p class="font-medium text-gray-700 dark:text-gray-300">
                            Aucune transaction
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Aucune transaction ne correspond à ce filtre.
                        </p>

                        <a
                            href="{{ route('accounts.transactions.create', $account) }}"
                            class="inline-block mt-5 px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                        >
                            + Ajouter une transaction
                        </a>

                    </div>

                @else

                    {{-- Desktop --}}
                    <div class="hidden md:block overflow-x-auto">

                        <table class="w-full">

                            <thead>

                                <tr class="text-left text-xs uppercase tracking-wider text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">

                                    <th class="px-6 py-4">
                                        Date
                                    </th>

                                    <th class="px-6 py-4">
                                        Catégorie
                                    </th>

                                    <th class="px-6 py-4">
                                        Description
                                    </th>

                                    <th class="px-6 py-4 text-right">
                                        Montant
                                    </th>

                                    <th class="px-6 py-4 text-right">
                                        Actions
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                                @foreach ($transactions as $transaction)

                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">


                                        {{-- Date --}}
                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $transaction->transaction_date->format('d/m/Y') }}
                                            </p>

                                        </td>


                                        {{-- Catégorie --}}
                                        <td class="px-6 py-4">

                                            @if ($transaction->transfer_id)

                                                <span class="inline-flex items-center gap-1 text-sm text-blue-600">
                                                    ↔️ Transfert
                                                </span>

                                            @elseif ($transaction->category)

                                                <span class="text-sm text-gray-700 dark:text-gray-300">

                                                    {{ $transaction->category->icon }}
                                                    {{ $transaction->category->name }}

                                                </span>

                                            @else

                                                <span class="text-sm text-gray-400">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Description --}}
                                        <td class="px-6 py-4">

                                            <p class="text-sm text-gray-700 dark:text-gray-300 max-w-md truncate">
                                                {{ $transaction->description ?: 'Sans description' }}
                                            </p>

                                        </td>


                                        {{-- Montant --}}
                                        <td class="px-6 py-4 text-right whitespace-nowrap">

                                            @if ($transaction->type === 'income')

                                                <span class="font-semibold text-green-600">
                                                    +{{ number_format($transaction->amount, 2, ',', ' ') }} €
                                                </span>

                                            @else

                                                <span class="font-semibold text-red-600">
                                                    -{{ number_format($transaction->amount, 2, ',', ' ') }} €
                                                </span>

                                            @endif

                                        </td>


                                        {{-- Actions --}}
                                        <td class="px-6 py-4">

                                            <div class="flex justify-end items-center gap-3">

                                                <a
                                                    href="{{ route('accounts.transactions.show', [$account, $transaction]) }}"
                                                    class="text-sm text-blue-600 hover:underline"
                                                >
                                                    Voir
                                                </a>

                                                <a
                                                    href="{{ route('accounts.transactions.edit', [$account, $transaction]) }}"
                                                    class="text-sm text-gray-500 hover:underline"
                                                >
                                                    Modifier
                                                </a>

                                                <form
                                                    method="POST"
                                                    action="{{ route('accounts.transactions.destroy', [$account, $transaction]) }}"
                                                >

                                                    @csrf
                                                    @method('DELETE')

                                                    <button
                                                        type="submit"
                                                        onclick="return confirm('Supprimer cette transaction ?')"
                                                        class="text-sm text-red-600 hover:underline"
                                                    >
                                                        Supprimer
                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- Mobile --}}
                    <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">

                        @foreach ($transactions as $transaction)

                            <div class="p-5">

                                <div class="flex justify-between items-start gap-4">

                                    <div class="min-w-0">

                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $transaction->transaction_date->format('d/m/Y') }}
                                        </p>

                                        <p class="font-medium text-gray-900 dark:text-white mt-1">

                                            @if ($transaction->transfer_id)

                                                ↔️ Transfert

                                            @elseif ($transaction->category)

                                                {{ $transaction->category->icon }}
                                                {{ $transaction->category->name }}

                                            @else

                                                Transaction

                                            @endif

                                        </p>

                                        <p class="text-sm text-gray-500 mt-1 truncate">
                                            {{ $transaction->description ?: 'Sans description' }}
                                        </p>

                                    </div>


                                    <div class="text-right shrink-0">

                                        @if ($transaction->type === 'income')

                                            <p class="font-semibold text-green-600">
                                                +{{ number_format($transaction->amount, 2, ',', ' ') }} €
                                            </p>

                                        @else

                                            <p class="font-semibold text-red-600">
                                                -{{ number_format($transaction->amount, 2, ',', ' ') }} €
                                            </p>

                                        @endif

                                    </div>

                                </div>


                                <div class="flex gap-4 mt-4">

                                    <a
                                        href="{{ route('accounts.transactions.show', [$account, $transaction]) }}"
                                        class="text-sm text-blue-600 hover:underline"
                                    >
                                        Voir
                                    </a>

                                    <a
                                        href="{{ route('accounts.transactions.edit', [$account, $transaction]) }}"
                                        class="text-sm text-gray-500 hover:underline"
                                    >
                                        Modifier
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('accounts.transactions.destroy', [$account, $transaction]) }}"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            onclick="return confirm('Supprimer cette transaction ?')"
                                            class="text-sm text-red-600 hover:underline"
                                        >
                                            Supprimer
                                        </button>

                                    </form>

                                </div>

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>


        </div>

    </div>

</x-app-layout>