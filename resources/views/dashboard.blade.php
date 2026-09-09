<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            MoneyFlow
        </h2>
    </x-slot>

    <div class="py-6">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- En-tête --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Bonjour {{ auth()->user()->name }} 👋
                </h1>

                <p class="mt-2 text-gray-500 dark:text-gray-400">
                    Voici où en sont tes finances.
                </p>
            </div>


            {{-- Comptes --}}
            <div class="mb-8">

                <div class="flex justify-between items-center mb-4">

                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        Mes comptes
                    </h2>

                    <a
                        href="{{ route('accounts.index') }}"
                        class="text-sm text-blue-600 hover:underline"
                    >
                        Gérer mes comptes →
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
                                $forecast = $accountData['forecast'];
                            @endphp

                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

                                <div class="flex justify-between items-start">

                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $account->name }}
                                        </h3>

                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $account->type }}
                                        </p>
                                    </div>

                                    <a
                                        href="{{ route('accounts.transactions.index', $account) }}"
                                        class="text-sm text-blue-600 hover:underline"
                                    >
                                        Transactions
                                    </a>

                                </div>


                                <div class="mt-6">

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Solde actuel
                                    </p>

                                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">
                                        {{ number_format($account->balance, 2, ',', ' ') }} €
                                    </p>

                                </div>


                                <div class="mt-5 pt-5 border-t border-gray-200 dark:border-gray-700">

                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Prévu fin du mois
                                    </p>

                                    <p class="text-2xl font-bold mt-1
                                        {{ $forecast >= $account->balance
                                            ? 'text-green-600'
                                            : 'text-red-600' }}"
                                    >
                                        {{ number_format($forecast, 2, ',', ' ') }} €
                                    </p>

                                </div>


                                @php
                                    $difference = $forecast - (float) $account->balance;
                                @endphp

                                @if ($difference != 0)

                                    <p class="text-sm text-gray-500 mt-3">

                                        @if ($difference > 0)
                                            +{{ number_format($difference, 2, ',', ' ') }} €
                                            prévus d'ici la fin du mois
                                        @else
                                            {{ number_format(abs($difference), 2, ',', ' ') }} €
                                            prévus à sortir d'ici la fin du mois
                                        @endif

                                    </p>

                                @else

                                    <p class="text-sm text-gray-500 mt-3">
                                        Aucun mouvement prévu sur ce compte.
                                    </p>

                                @endif

                            </div>

                        @endforeach

                    </div>

                @endif

            </div>


            {{-- Raccourcis --}}
            <div>

                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                    Actions rapides
                </h2>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                    <a
                        href="{{ route('accounts.index') }}"
                        class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 hover:bg-gray-50 dark:hover:bg-gray-700"
                    >
                        <p class="font-semibold text-gray-900 dark:text-white">
                            💳 Comptes
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Gérer mes comptes
                        </p>
                    </a>


                    <a
                        href="{{ route('recurring-transactions.index') }}"
                        class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 hover:bg-gray-50 dark:hover:bg-gray-700"
                    >
                        <p class="font-semibold text-gray-900 dark:text-white">
                            🔄 Récurrences
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Revenus et dépenses réguliers
                        </p>
                    </a>


                    <a
                        href="{{ route('categories.index') }}"
                        class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 hover:bg-gray-50 dark:hover:bg-gray-700"
                    >
                        <p class="font-semibold text-gray-900 dark:text-white">
                            🏷️ Catégories
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Personnaliser mes catégories
                        </p>
                    </a>


                    <a
                        href="{{ route('transfers.create') }}"
                        class="bg-white dark:bg-gray-800 rounded-xl shadow p-5 hover:bg-gray-50 dark:hover:bg-gray-700"
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