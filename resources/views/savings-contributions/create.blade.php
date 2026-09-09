<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ajouter une contribution
        </h2>
    </x-slot>

    <div class="py-6">

        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

                <div class="mb-6">

                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Ajouter une contribution
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Objectif : {{ $savingsGoal->name }}
                    </p>

                </div>

                <div class="mb-6 p-4 rounded-lg bg-gray-50 dark:bg-gray-700">

                    <div class="flex justify-between items-center">

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-300">
                                Objectif
                            </p>

                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ number_format($savingsGoal->target_amount, 2, ',', ' ') }} €
                            </p>
                        </div>

                        <div class="text-right">
                            <p class="text-sm text-gray-500 dark:text-gray-300">
                                Déjà épargné
                            </p>

                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ number_format($savingsGoal->contributions->sum('amount'), 2, ',', ' ') }} €
                            </p>
                        </div>

                    </div>

                </div>

                <form
                    method="POST"
                    action="{{ route('savings-goals.contributions.store', $savingsGoal) }}"
                    class="space-y-5"
                >

                    @csrf

                    <div>

                        <label
                            for="amount"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Montant de la contribution
                        </label>

                        <input
                            id="amount"
                            name="amount"
                            type="number"
                            step="0.01"
                            min="0.01"
                            value="{{ old('amount') }}"
                            placeholder="300.00"
                            required
                            autofocus
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                        >

                        @error('amount')
                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <div>

                        <label
                            for="contribution_date"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Date
                        </label>

                        <input
                            id="contribution_date"
                            name="contribution_date"
                            type="date"
                            value="{{ old('contribution_date', now()->format('Y-m-d')) }}"
                            required
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                        >

                        @error('contribution_date')
                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <div>

                        <label
                            for="notes"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Note
                            <span class="text-gray-400">(facultative)</span>
                        </label>

                        <textarea
                            id="notes"
                            name="notes"
                            rows="3"
                            placeholder="Ex : Argent mis de côté ce mois-ci"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                        >{{ old('notes') }}</textarea>

                        @error('notes')
                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <div class="flex gap-3 pt-4">

                        <a
                            href="{{ route('savings-goals.index') }}"
                            class="px-4 py-2 rounded-lg border"
                        >
                            Annuler
                        </a>

                        <button
                            type="submit"
                            class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                        >
                            Ajouter la contribution
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>