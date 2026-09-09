<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Modifier la transaction — {{ $account->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Modifier la transaction
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Compte : {{ $account->name }}
                    </p>
                </div>

                <form method="POST"
                      action="{{ route('accounts.transactions.update', [$account, $transaction]) }}"
                      class="space-y-5">

                    @csrf
                    @method('PUT')

                    {{-- Type --}}
                    <div>
                        <label for="type"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Type
                        </label>

                        <select id="type"
                                name="type"
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                                required>

                            <option value="expense"
                                {{ old('type', $transaction->type) === 'expense' ? 'selected' : '' }}>
                                Dépense
                            </option>

                            <option value="income"
                                {{ old('type', $transaction->type) === 'income' ? 'selected' : '' }}>
                                Revenu
                            </option>

                        </select>

                        @error('type')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Montant --}}
                    <div>
                        <label for="amount"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Montant
                        </label>

                        <input id="amount"
                               name="amount"
                               type="number"
                               step="0.01"
                               min="0.01"
                               value="{{ old('amount', $transaction->amount) }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                               required>

                        @error('amount')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Catégorie --}}
                    <div>
                        <label for="category_id"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Catégorie
                        </label>

                        <select id="category_id"
                                name="category_id"
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white">

                            <option value="">Aucune catégorie</option>

                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $transaction->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach

                        </select>

                        @error('category_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Description
                        </label>

                        <input id="description"
                               name="description"
                               type="text"
                               value="{{ old('description', $transaction->description) }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white">

                        @error('description')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Date --}}
                    <div>
                        <label for="transaction_date"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Date
                        </label>

                        <input id="transaction_date"
                               name="transaction_date"
                               type="date"
                               value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                               required>

                        @error('transaction_date')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3 pt-4">

                        <a href="{{ route('accounts.transactions.index', $account) }}"
                           class="px-4 py-2 rounded-lg border">
                            Annuler
                        </a>

                        <button type="submit"
                                class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
                            Enregistrer
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>