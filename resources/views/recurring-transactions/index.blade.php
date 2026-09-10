<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Récurrences
            </h2>

            <a
                href="{{ route('recurring-transactions.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-lg font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white transition"
            >
                + Ajouter
            </a>

        </div>

    </x-slot>


    <div class="py-6">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


            {{-- ===================================================== --}}
            {{-- EN-TÊTE --}}
            {{-- ===================================================== --}}

            <div class="mb-8">

                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Mes récurrences
                </h1>

                <p class="mt-2 text-gray-500 dark:text-gray-400">
                    Les revenus et dépenses réguliers utilisés pour prévoir ton budget.
                </p>

            </div>


            {{-- ===================================================== --}}
            {{-- MESSAGE DE SUCCÈS --}}
            {{-- ===================================================== --}}

            @if (session('success'))

                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-xl p-4">

                    {{ session('success') }}

                </div>

            @endif


            {{-- ===================================================== --}}
            {{-- ERREURS --}}
            {{-- ===================================================== --}}

            @if ($errors->any())

                <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 rounded-xl p-4">

                    <ul class="list-disc list-inside space-y-1">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- ===================================================== --}}
            {{-- EXPLICATION --}}
            {{-- ===================================================== --}}

            <div class="mb-8 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-5">

                <div class="flex gap-3">

                    <div class="text-xl">
                        💡
                    </div>

                    <div>

                        <h2 class="font-semibold text-blue-900 dark:text-blue-200">
                            Comment fonctionnent les récurrences ?
                        </h2>

                        <p class="text-sm text-blue-800 dark:text-blue-300 mt-1">
                            Une récurrence est une règle utilisée pour prévoir un mouvement
                            futur. Elle ne modifie pas ton solde réel et ne crée pas
                            automatiquement de transaction.
                        </p>

                    </div>

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- LISTE --}}
            {{-- ===================================================== --}}

            @if ($recurringTransactions->isEmpty())

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-8 text-center">

                    <div class="text-4xl mb-4">
                        🔄
                    </div>

                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        Aucune récurrence
                    </h2>

                    <p class="text-gray-500 dark:text-gray-400 mt-2">
                        Ajoute ton salaire, ton loyer, tes abonnements ou toute autre
                        dépense régulière.
                    </p>

                    <a
                        href="{{ route('recurring-transactions.create') }}"
                        class="inline-flex items-center mt-5 px-4 py-2 bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 rounded-lg hover:bg-gray-700 dark:hover:bg-white transition"
                    >
                        + Ajouter une récurrence
                    </a>

                </div>

            @else

                <div class="space-y-4">

                    @foreach ($recurringTransactions as $recurring)

                        @php

                            $isIncome = $recurring->type === 'income';

                            $frequencyLabel = match ($recurring->frequency) {
                                'daily' => 'Tous les jours',
                                'weekly' => 'Toutes les semaines',
                                'monthly' => 'Tous les mois',
                                'yearly' => 'Tous les ans',
                                default => ucfirst($recurring->frequency),
                            };

                        @endphp


                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 sm:p-6">

                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">


                                {{-- ================================================= --}}
                                {{-- INFORMATIONS --}}
                                {{-- ================================================= --}}

                                <div class="flex items-start gap-4">


                                    {{-- Icône --}}
                                    <div
                                        class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0
                                            {{ $isIncome
                                                ? 'bg-green-100 dark:bg-green-900/30'
                                                : 'bg-red-100 dark:bg-red-900/30' }}"
                                    >

                                        <span class="text-xl">

                                            {{ $isIncome ? '💰' : '💸' }}

                                        </span>

                                    </div>


                                    <div class="min-w-0">

                                        <div class="flex flex-wrap items-center gap-2">

                                            <h3 class="font-semibold text-gray-900 dark:text-white">

                                                {{ $recurring->name }}

                                            </h3>


                                            @if ($recurring->is_active)

                                                <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">

                                                    Active

                                                </span>

                                            @else

                                                <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300">

                                                    Désactivée

                                                </span>

                                            @endif

                                        </div>


                                        <div class="mt-2 flex flex-wrap gap-x-4 gap-y-1 text-sm text-gray-500 dark:text-gray-400">

                                            <span>
                                                {{ $recurring->account->name }}
                                            </span>

                                            <span>
                                                {{ $frequencyLabel }}
                                            </span>

                                            @if ($recurring->category)

                                                <span>
                                                    {{ $recurring->category->icon }}
                                                    {{ $recurring->category->name }}
                                                </span>

                                            @endif

                                        </div>


                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">

                                            Prochaine échéance :
                                            <span class="font-medium text-gray-700 dark:text-gray-300">

                                                {{ $recurring->next_occurrence->format('d/m/Y') }}

                                            </span>

                                        </p>

                                    </div>

                                </div>


                                {{-- ================================================= --}}
                                {{-- MONTANT + ACTIONS --}}
                                {{-- ================================================= --}}

                                <div class="flex flex-col sm:flex-row sm:items-center gap-4 lg:justify-end">


                                    {{-- Montant --}}
                                    <div class="text-left sm:text-right">

                                        <p
                                            class="text-2xl font-bold
                                                {{ $isIncome
                                                    ? 'text-green-600'
                                                    : 'text-red-600' }}"
                                        >

                                            {{ $isIncome ? '+' : '-' }}

                                            {{ number_format(
                                                (float) $recurring->amount,
                                                2,
                                                ',',
                                                ' '
                                            ) }}

                                            €

                                        </p>

                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">

                                            {{ $isIncome ? 'Revenu prévu' : 'Dépense prévue' }}

                                        </p>

                                    </div>


                                    {{-- Actions --}}
                                    <div class="flex gap-2">


                                        {{-- Modifier --}}
                                        <a
                                            href="{{ route('recurring-transactions.edit', $recurring) }}"
                                            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition text-sm"
                                        >
                                            Modifier
                                        </a>


                                        {{-- Supprimer --}}
                                        <form
                                            method="POST"
                                            action="{{ route('recurring-transactions.destroy', $recurring) }}"
                                            onsubmit="return confirm('Supprimer cette récurrence ?');"
                                        >

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="px-4 py-2 rounded-lg border border-red-200 dark:border-red-800 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition text-sm"
                                            >
                                                Supprimer
                                            </button>

                                        </form>

                                    </div>


                                </div>


                            </div>

                        </div>

                    @endforeach

                </div>

            @endif


            {{-- ===================================================== --}}
            {{-- RETOUR --}}
            {{-- ===================================================== --}}

            <div class="mt-8">

                <a
                    href="{{ route('dashboard') }}"
                    class="text-sm text-blue-600 hover:underline"
                >
                    ← Retour au dashboard
                </a>

            </div>


        </div>

    </div>

</x-app-layout>