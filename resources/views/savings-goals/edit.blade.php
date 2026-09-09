<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Modifier un objectif
        </h2>
    </x-slot>

    <div class="py-6">

        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

                <div class="mb-6">

                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Modifier l'objectif
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        {{ $savingsGoal->name }}
                    </p>

                </div>

                <form method="POST" action="{{ route('savings-goals.update', $savingsGoal) }}">
                    @csrf
                    @method('PUT')

                    <div>

                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Nom de l'objectif
                        </label>

                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $savingsGoal->name) }}"
                            required
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                        >

                        @error('name')
                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <div class="mt-4">

                        <label
                            for="target_amount"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Montant à atteindre
                        </label>

                        <input
                            id="target_amount"
                            name="target_amount"
                            type="number"
                            step="0.01"
                            min="0.01"
                            value="{{ old('target_amount', $savingsGoal->target_amount) }}"
                            required
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                        >

                        @error('target_amount')
                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>

                    <div class="mt-4">

                        <label
                            for="target_date"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Date limite
                            <span class="text-gray-400">(facultative)</span>
                        </label>

                        <input
                            id="target_date"
                            name="target_date"
                            type="date"
                            value="{{ old('target_date', $savingsGoal->target_date?->format('Y-m-d')) }}"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                        >

                        @error('target_date')
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
                            Enregistrer
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>