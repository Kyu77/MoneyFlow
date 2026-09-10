<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ajouter une transaction
        </h2>

    </x-slot>


    <div class="py-4 sm:py-6">

        <div
            class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8"
            x-data="transactionForm()"
        >


            {{-- ===================================================== --}}
            {{-- EN-TÊTE --}}
            {{-- ===================================================== --}}

            <div class="mb-5 sm:mb-7">

                <a
                    href="{{ $account
                        ? route('accounts.transactions.index', $account)
                        : route('dashboard') }}"
                    class="text-sm text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
                >
                    ← Retour
                </a>

                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mt-4">
                    Ajouter une transaction
                </h1>

                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Enregistre rapidement ton mouvement.
                </p>

            </div>


            {{-- ===================================================== --}}
            {{-- ERREURS --}}
            {{-- ===================================================== --}}

            @if ($errors->any())

                <div class="mb-5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">

                    <ul class="text-sm text-red-600 dark:text-red-400 space-y-1">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- ===================================================== --}}
            {{-- FORMULAIRE --}}
            {{-- ===================================================== --}}

            <form
                method="POST"
                action="{{ $account
                    ? route('accounts.transactions.store', $account)
                    : route('transactions.store') }}"
                class="space-y-4 sm:space-y-5"
            >

                @csrf


                {{-- ================================================= --}}
                {{-- TYPE --}}
                {{-- ================================================= --}}

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-2 grid grid-cols-2 gap-2">

                    <button
                        type="button"
                        @click="type = 'expense'"
                        :class="type === 'expense'
                            ? 'bg-red-600 text-white shadow'
                            : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'"
                        class="min-h-[52px] rounded-xl font-semibold transition"
                    >
                        Dépense
                    </button>

                    <button
                        type="button"
                        @click="type = 'income'"
                        :class="type === 'income'
                            ? 'bg-green-600 text-white shadow'
                            : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'"
                        class="min-h-[52px] rounded-xl font-semibold transition"
                    >
                        Revenu
                    </button>

                    <input
                        type="hidden"
                        name="type"
                        :value="type"
                    >

                </div>


                {{-- ================================================= --}}
                {{-- MONTANT --}}
                {{-- ================================================= --}}

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 sm:p-6">

                    <label
                        for="amount"
                        class="block text-sm font-medium text-gray-600 dark:text-gray-300"
                    >
                        Montant
                    </label>

                    <div class="relative mt-2">

                        <input
                            id="amount"
                            name="amount"
                            type="number"
                            inputmode="decimal"
                            step="0.01"
                            min="0.01"
                            value="{{ old('amount') }}"
                            placeholder="0,00"
                            autofocus
                            required
                            class="w-full border-0 border-b-2 border-gray-200 dark:border-gray-600 bg-transparent dark:text-white text-4xl sm:text-5xl font-bold text-center py-3 focus:border-gray-800 dark:focus:border-white focus:ring-0"
                        >

                        <span class="absolute right-2 bottom-4 text-xl text-gray-400">
                            €
                        </span>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- CATÉGORIE --}}
                {{-- ================================================= --}}

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 sm:p-6">

                    <div class="flex items-center justify-between mb-4">

                        <label class="text-sm font-medium text-gray-600 dark:text-gray-300">
                            Catégorie
                        </label>

                    </div>


                    {{-- Dépenses --}}
                    <div
                        x-show="type === 'expense'"
                        class="grid grid-cols-4 gap-2"
                    >

                        @foreach ($categories as $category)

                            <button
                                type="button"
                                @click="selectCategory('{{ $category->id }}')"
                                :class="categoryId == '{{ $category->id }}'
                                    ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900 ring-2 ring-offset-2 ring-gray-900 dark:ring-white dark:ring-offset-gray-800'
                                    : 'bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600'"
                                class="min-h-[70px] rounded-xl p-2 flex flex-col items-center justify-center transition"
                            >

                                <span class="text-xl">
                                    {{ $category->icon }}
                                </span>

                                <span class="text-[11px] leading-tight text-center mt-1">
                                    {{ $category->name }}
                                </span>

                            </button>

                        @endforeach

                    </div>


                    {{-- Revenus --}}
                    <div
                        x-show="type === 'income'"
                        class="grid grid-cols-4 gap-2"
                    >

                        @foreach ($incomeCategories as $category)

                            <button
                                type="button"
                                @click="selectCategory('{{ $category->id }}')"
                                :class="categoryId == '{{ $category->id }}'
                                    ? 'bg-gray-900 text-white dark:bg-white dark:text-gray-900 ring-2 ring-offset-2 ring-gray-900 dark:ring-white dark:ring-offset-gray-800'
                                    : 'bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600'"
                                class="min-h-[70px] rounded-xl p-2 flex flex-col items-center justify-center transition"
                            >

                                <span class="text-xl">
                                    {{ $category->icon }}
                                </span>

                                <span class="text-[11px] leading-tight text-center mt-1">
                                    {{ $category->name }}
                                </span>

                            </button>

                        @endforeach

                    </div>


                    <input
                        type="hidden"
                        name="category_id"
                        :value="categoryId"
                    >

                </div>


                {{-- ================================================= --}}
                {{-- COMPTE --}}
                {{-- ================================================= --}}

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 sm:p-6">

                    <label
                        for="account_id"
                        class="block text-sm font-medium text-gray-600 dark:text-gray-300 mb-2"
                    >
                        Compte
                    </label>


                    @if ($account)

                        {{-- Compte déjà sélectionné --}}
                        <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 rounded-xl px-4 py-4">

                            <div class="flex items-center gap-3">

                                <span class="text-xl">
                                    💳
                                </span>

                                <div>

                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ $account->name }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        Compte sélectionné
                                    </p>

                                </div>

                            </div>

                        </div>

                    @else

                        <select
                            id="account_id"
                            name="account_id"
                            required
                            class="w-full min-h-[52px] rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-gray-800 focus:ring-gray-800"
                        >

                            <option value="">
                                Choisir un compte
                            </option>

                            @foreach ($accounts as $availableAccount)

                                <option
                                    value="{{ $availableAccount->id }}"
                                    @selected(
                                        old('account_id') == $availableAccount->id
                                        || (
                                            !old('account_id')
                                            && $account
                                            && $account->id === $availableAccount->id
                                        )
                                    )
                                >
                                    {{ $availableAccount->name }}
                                </option>

                            @endforeach

                        </select>

                    @endif


                    @if ($account)

                        <input
                            type="hidden"
                            name="account_id"
                            value="{{ $account->id }}"
                        >

                    @endif

                </div>


                {{-- ================================================= --}}
                {{-- DÉTAILS OPTIONNELS --}}
                {{-- ================================================= --}}

                <details class="bg-white dark:bg-gray-800 rounded-2xl shadow">

                    <summary class="cursor-pointer list-none p-5 sm:p-6 font-medium text-gray-700 dark:text-gray-200">

                        <div class="flex justify-between items-center">

                            <span>
                                Ajouter un détail
                            </span>

                            <span class="text-gray-400">
                                +
                            </span>

                        </div>

                    </summary>


                    <div class="px-5 pb-5 sm:px-6 sm:pb-6 space-y-4">


                        {{-- Description --}}
                        <div>

                            <label
                                for="description"
                                class="block text-sm font-medium text-gray-600 dark:text-gray-300"
                            >
                                Description
                            </label>

                            <input
                                id="description"
                                name="description"
                                type="text"
                                value="{{ old('description') }}"
                                placeholder="Ex : Courses, restaurant..."
                                class="mt-2 w-full min-h-[50px] rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            >

                        </div>


                        {{-- Date --}}
                        <div>

                            <label
                                for="transaction_date"
                                class="block text-sm font-medium text-gray-600 dark:text-gray-300"
                            >
                                Date
                            </label>

                            <input
                                id="transaction_date"
                                name="transaction_date"
                                type="date"
                                value="{{ old('transaction_date', now()->format('Y-m-d')) }}"
                                required
                                class="mt-2 w-full min-h-[50px] rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            >

                        </div>

                    </div>

                </details>


                {{-- ================================================= --}}
                {{-- BOUTON --}}
                {{-- ================================================= --}}

                <div class="pt-2 pb-4">

                    <button
                        type="submit"
                        class="w-full min-h-[58px] rounded-2xl bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-bold text-base shadow-lg hover:bg-gray-800 dark:hover:bg-gray-100 active:scale-[0.99] transition"
                    >
                        Ajouter la transaction
                    </button>

                </div>


            </form>


        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ALPINE --}}
    {{-- ========================================================= --}}

    <script>

        function transactionForm() {

            return {

                type: '{{ old('type', 'expense') }}',

                categoryId: '{{ old('category_id', '') }}',

                selectCategory(id) {

                    this.categoryId = id;

                },

            }

        }

    </script>

</x-app-layout>