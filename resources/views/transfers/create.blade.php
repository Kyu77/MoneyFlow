<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Nouveau transfert
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Transférer de l'argent
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Déplacez de l'argent d'un compte vers un autre.
                    </p>
                </div>

                <form
                    method="POST"
                    action="{{ route('transfers.store') }}"
                    class="space-y-5"
                    x-data="{
                        fromAccount: '{{ old('from_account_id', '') }}',
                        toAccount: '{{ old('to_account_id', '') }}'
                    }"
                >
                    @csrf

                    {{-- Compte source --}}
                    <div>
                        <label
                            for="from_account_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Depuis le compte
                        </label>

                        <select
                            id="from_account_id"
                            name="from_account_id"
                            x-model="fromAccount"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                            required
                        >
                            <option value="">
                                Sélectionner un compte
                            </option>

                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">
                                    {{ $account->name }} —
                                    {{ number_format($account->balance, 2, ',', ' ') }} €
                                </option>
                            @endforeach
                        </select>

                        @error('from_account_id')
                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Compte destination --}}
                    <div>
                        <label
                            for="to_account_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Vers le compte
                        </label>

                        <select
                            id="to_account_id"
                            name="to_account_id"
                            x-model="toAccount"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                            required
                        >
                            <option value="">
                                Sélectionner un compte
                            </option>

                            @foreach ($accounts as $account)
                                <option
                                    value="{{ $account->id }}"
                                    :disabled="fromAccount == '{{ $account->id }}'"
                                >
                                    {{ $account->name }} —
                                    {{ number_format($account->balance, 2, ',', ' ') }} €
                                </option>
                            @endforeach
                        </select>

                        @error('to_account_id')
                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Montant --}}
                    <div>
                        <label
                            for="amount"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Montant
                        </label>

                        <input
                            id="amount"
                            name="amount"
                            type="number"
                            step="0.01"
                            min="0.01"
                            value="{{ old('amount') }}"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                            placeholder="0,00"
                            required
                        >

                        @error('amount')
                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label
                            for="description"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Description
                        </label>

                        <input
                            id="description"
                            name="description"
                            type="text"
                            value="{{ old('description') }}"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                            placeholder="Ex : Épargne du mois"
                        >

                        @error('description')
                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Date --}}
                    <div>
                        <label
                            for="transaction_date"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Date
                        </label>

                        <input
                            id="transaction_date"
                            name="transaction_date"
                            type="date"
                            value="{{ old('transaction_date', now()->format('Y-m-d')) }}"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                            required
                        >

                        @error('transaction_date')
                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Boutons --}}
                    <div class="flex gap-3 pt-4">

                        <a
                            href="{{ route('accounts.index') }}"
                            class="px-4 py-2 rounded-lg border"
                        >
                            Annuler
                        </a>

                        <button
                            type="submit"
                            class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                        >
                            Effectuer le transfert
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>