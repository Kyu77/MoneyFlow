<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $account->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $account->name }}
                        </h1>

                        <p class="mt-1 text-gray-500">
                            {{ $account->type }}
                        </p>
                    </div>

                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ number_format($account->balance, 2, ',', ' ') }} €
                    </p>
                </div>

                <div class="mt-6 flex gap-3">
                    <a href="{{ route('accounts.transactions.index', $account) }}"
                       class="px-4 py-2 rounded-lg border">
                        Voir les transactions
                    </a>

                    <a href="{{ route('accounts.transactions.create', $account) }}"
                       class="px-4 py-2 bg-gray-800 text-white rounded-lg">
                        + Transaction
                    </a>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>