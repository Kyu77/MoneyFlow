<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Mes comptes
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Mes comptes
                </h1>

                <div class="flex gap-2">
                    <a
                        href="{{ route('transfers.create') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500"
                    >
                        ↔ Transférer
                    </a>

                    <a
                        href="{{ route('accounts.create') }}"
                        class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                    >
                        + Ajouter
                    </a>
                </div>
            </div>

            <div class="grid gap-4">

                @forelse ($accounts as $account)

                    <div class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow">

                        <div class="flex justify-between items-center">

                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $account->name }}
                                </h2>

                                <p class="text-sm text-gray-500">
                                    {{ $account->type }}
                                </p>
                            </div>

                            <p class="text-xl font-bold text-gray-900 dark:text-white">
                                {{ number_format($account->balance, 2, ',', ' ') }} €
                            </p>

                        </div>

                        {{-- Actions du compte --}}
                        <div class="flex gap-2 mt-4">

                            <a
                                href="{{ route('accounts.show', $account) }}"
                                class="px-3 py-2 rounded-lg border text-sm"
                            >
                                Voir
                            </a>

                            <a
                                href="{{ route('accounts.edit', $account) }}"
                                class="px-3 py-2 rounded-lg border text-sm"
                            >
                                Modifier
                            </a>

                            <form
                                method="POST"
                                action="{{ route('accounts.destroy', $account) }}"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="px-3 py-2 rounded-lg border text-sm"
                                >
                                    Supprimer
                                </button>
                            </form>

                        </div>

                    </div>

                @empty

                    <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow text-gray-600 dark:text-gray-300">
                        Aucun compte pour le moment.
                    </div>

                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>