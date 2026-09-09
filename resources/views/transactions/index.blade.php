<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Transactions — {{ $account->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Transactions
                    </h1>

                    <p class="text-sm text-gray-500">
                        {{ $account->name }}
                    </p>
                </div>

                <a
                    href="{{ route('accounts.transactions.create', $account) }}"
                    class="px-4 py-2 bg-gray-800 text-white rounded-lg"
                >
                    + Ajouter
                </a>
            </div>

            <div class="space-y-3">
                @forelse ($transactions as $transaction)
                    <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow">

                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="font-semibold text-gray-900 dark:text-white">
                                    {{ $transaction->description ?? 'Transaction' }}
                                </h2>

                                <p class="text-sm text-gray-500">
                                    {{ $transaction->transaction_date->format('d/m/Y') }}

                                    @if ($transaction->category)
                                        · {{ $transaction->category->icon }}
                                        {{ $transaction->category->name }}
                                    @endif
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="font-bold {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->type === 'income' ? '+' : '-' }}
                                    {{ number_format($transaction->amount, 2, ',', ' ') }} €
                                </p>

                                <div class="flex gap-2 justify-end mt-2">
                                    <a
                                        href="{{ route('accounts.transactions.edit', [$account, $transaction]) }}"
                                        class="text-sm text-blue-600 hover:underline"
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
                                            class="text-sm text-red-600 hover:underline"
                                        >
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                @empty
                    <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow">
                        <p class="text-gray-600 dark:text-gray-300">
                            Aucune transaction pour ce compte.
                        </p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                <a
                    href="{{ route('accounts.show', $account) }}"
                    class="text-sm text-gray-600 dark:text-gray-300"
                >
                    ← Retour au compte
                </a>
            </div>

        </div>
    </div>
</x-app-layout>