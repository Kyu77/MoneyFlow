<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Transactions récurrentes
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Transactions récurrentes
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Gérez vos revenus et dépenses qui reviennent régulièrement.
                    </p>
                </div>

                <a
                    href="{{ route('recurring-transactions.create') }}"
                    class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                >
                    + Ajouter
                </a>
            </div>

                        @if ($dueRecurringTransactions->isNotEmpty())

                <div class="mb-8">

                    <div class="mb-4">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                            🔔 Échéances à confirmer
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Ces opérations sont arrivées à échéance et attendent votre confirmation.
                        </p>
                    </div>

                    <div class="space-y-4">

                        @foreach ($dueRecurringTransactions as $recurring)

                            <div class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow border border-yellow-300 dark:border-yellow-600">

                                <div class="flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center">

                                    <div>

                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ $recurring->name }}
                                        </h3>

                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $recurring->account->name }}

                                            @if ($recurring->category)
                                                · {{ $recurring->category->icon }}
                                                {{ $recurring->category->name }}
                                            @endif
                                        </p>

                                        <p class="text-sm text-gray-500 mt-1">
                                            Échéance :
                                            {{ $recurring->next_occurrence->format('d/m/Y') }}
                                        </p>

                                    </div>

                                    <div class="flex flex-col sm:items-end gap-3">

                                        <p class="text-xl font-bold {{ $recurring->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $recurring->type === 'income' ? '+' : '-' }}
                                            {{ number_format($recurring->amount, 2, ',', ' ') }} €
                                        </p>

                                        <div class="flex gap-2">

                                            <form
                                                method="POST"
                                                action="{{ route('recurring-transactions.confirm', $recurring) }}"
                                            >
                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-500"
                                                >
                                                    ✓ Confirmer
                                                </button>
                                            </form>

                                            <form
                                                method="POST"
                                                action="{{ route('recurring-transactions.ignore', $recurring) }}"
                                            >
                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                                >
                                                    Ignorer
                                                </button>
                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            @endif

            <div class="space-y-4">

                @forelse ($recurringTransactions as $recurring)

                    <div class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow">

                        <div class="flex justify-between items-start gap-4">

                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $recurring->name }}
                                </h2>

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $recurring->account->name }}

                                    @if ($recurring->category)
                                        · {{ $recurring->category->icon }}
                                        {{ $recurring->category->name }}
                                    @endif
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    @switch($recurring->frequency)
                                        @case('daily')
                                            Tous les jours
                                            @break

                                        @case('weekly')
                                            Toutes les semaines
                                            @break

                                        @case('monthly')
                                            Tous les mois
                                            @break

                                        @case('yearly')
                                            Tous les ans
                                            @break
                                    @endswitch
                                </p>

                                <p class="text-sm text-gray-500 mt-1">
                                    Prochaine échéance :
                                    {{ $recurring->next_occurrence->format('d/m/Y') }}
                                </p>

                                @if ($recurring->is_active)
                                    <span class="inline-block mt-2 px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-block mt-2 px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600">
                                        Désactivée
                                    </span>
                                @endif
                            </div>

                            <div class="text-right shrink-0">

                                <p class="text-xl font-bold {{ $recurring->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $recurring->type === 'income' ? '+' : '-' }}
                                    {{ number_format($recurring->amount, 2, ',', ' ') }} €
                                </p>

                                <div class="flex gap-2 justify-end mt-3">

                                    <a
                                        href="{{ route('recurring-transactions.edit', $recurring) }}"
                                        class="text-sm text-blue-600 hover:underline"
                                    >
                                        Modifier
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('recurring-transactions.destroy', $recurring) }}"
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
                            Aucune transaction récurrente pour le moment.
                        </p>
                    </div>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>