<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Modifier une transaction récurrente
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Modifier la récurrence
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        {{ $recurringTransaction->name }}
                    </p>
                </div>

                <form
                    method="POST"
                    action="{{ route('recurring-transactions.update', $recurringTransaction) }}"
                    class="space-y-5"
                    x-data="{
                        type: '{{ old('type', $recurringTransaction->type) }}',
                        categories: @js($categories),
                        selectedCategory: '{{ old('category_id', $recurringTransaction->category_id ?? '') }}',

                        get filteredCategories() {
                            return this.categories.filter(
                                category => category.type === this.type
                            );
                        }
                    }"
                >
                    @csrf
                    @method('PUT')

                    {{-- Nom --}}
                    <div>
                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Nom
                        </label>

                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $recurringTransaction->name) }}"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                            required
                        >

                        @error('name')
                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Type --}}
                    <div>
                        <label
                            for="type"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Type
                        </label>

                        <select
                            id="type"
                            name="type"
                            x-model="type"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                            required
                        >
                            <option value="expense">
                                Dépense
                            </option>

                            <option value="income">
                                Revenu
                            </option>
                        </select>

                        @error('type')
                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Compte --}}
                    <div>
                        <label
                            for="account_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Compte
                        </label>

                        <select
                            id="account_id"
                            name="account_id"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                            required
                        >
                            @foreach ($accounts as $account)
                                <option
                                    value="{{ $account->id }}"
                                    {{ old('account_id', $recurringTransaction->account_id) == $account->id ? 'selected' : '' }}
                                >
                                    {{ $account->name }} —
                                    {{ number_format($account->balance, 2, ',', ' ') }} €
                                </option>
                            @endforeach
                        </select>

                        @error('account_id')
                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Catégorie --}}
                    <div>
                        <label
                            for="category_id"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Catégorie
                        </label>

                        <select
                            id="category_id"
                            name="category_id"
                            x-model="selectedCategory"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="">
                                Aucune catégorie
                            </option>

                            <template
                                x-for="category in filteredCategories"
                                :key="category.id"
                            >
                                <option
                                    :value="category.id"
                                    x-text="`${category.icon ?? ''} ${category.name}`"
                                ></option>
                            </template>
                        </select>

                        @error('category_id')
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
                            value="{{ old('amount', $recurringTransaction->amount) }}"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                            required
                        >

                        @error('amount')
                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Fréquence --}}
                    <div>
                        <label
                            for="frequency"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Fréquence
                        </label>

                        <select
                            id="frequency"
                            name="frequency"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                            required
                        >
                            <option value="daily" {{ old('frequency', $recurringTransaction->frequency) === 'daily' ? 'selected' : '' }}>
                                Tous les jours
                            </option>

                            <option value="weekly" {{ old('frequency', $recurringTransaction->frequency) === 'weekly' ? 'selected' : '' }}>
                                Toutes les semaines
                            </option>

                            <option value="monthly" {{ old('frequency', $recurringTransaction->frequency) === 'monthly' ? 'selected' : '' }}>
                                Tous les mois
                            </option>

                            <option value="yearly" {{ old('frequency', $recurringTransaction->frequency) === 'yearly' ? 'selected' : '' }}>
                                Tous les ans
                            </option>
                        </select>

                        @error('frequency')
                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Prochaine échéance --}}
                    <div>
                        <label
                            for="next_occurrence"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Prochaine échéance
                        </label>

                        <input
                            id="next_occurrence"
                            name="next_occurrence"
                            type="date"
                            value="{{ old('next_occurrence', $recurringTransaction->next_occurrence->format('Y-m-d')) }}"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                            required
                        >

                        @error('next_occurrence')
                            <p class="text-sm text-red-600 mt-1">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Active --}}
                    <div class="flex items-center gap-3">
                        <input
                            id="is_active"
                            name="is_active"
                            type="checkbox"
                            value="1"
                            {{ old('is_active', $recurringTransaction->is_active) ? 'checked' : '' }}
                            class="rounded border-gray-300"
                        >

                        <label
                            for="is_active"
                            class="text-sm text-gray-700 dark:text-gray-300"
                        >
                            Récurrence active
                        </label>
                    </div>

                    {{-- Boutons --}}
                    <div class="flex gap-3 pt-4">

                        <a
                            href="{{ route('recurring-transactions.index') }}"
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