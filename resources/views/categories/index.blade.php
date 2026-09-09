<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Mes catégories
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
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Mes catégories
                </h1>

                <a href="{{ route('categories.create') }}"
                   class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700">
                    + Ajouter
                </a>
            </div>

            <div class="space-y-6">

                {{-- Dépenses --}}
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                        Dépenses
                    </h2>

                    <div class="space-y-2">
                        @forelse ($categories->where('type', 'expense') as $category)

                            <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow flex justify-between items-center">

                                <div class="flex items-center gap-3">
                                    @if ($category->icon)
                                        <span class="text-xl">
                                            {{ $category->icon }}
                                        </span>
                                    @endif

                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $category->name }}
                                    </span>
                                </div>

                                <div class="flex gap-3">
                                    <a href="{{ route('categories.edit', $category) }}"
                                       class="text-sm text-blue-600 hover:underline">
                                        Modifier
                                    </a>

                                    <form method="POST"
                                          action="{{ route('categories.destroy', $category) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="text-sm text-red-600 hover:underline">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>

                            </div>

                        @empty
                            <p class="text-gray-500">
                                Aucune catégorie de dépense.
                            </p>
                        @endforelse
                    </div>
                </div>

                {{-- Revenus --}}
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-3">
                        Revenus
                    </h2>

                    <div class="space-y-2">
                        @forelse ($categories->where('type', 'income') as $category)

                            <div class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow flex justify-between items-center">

                                <div class="flex items-center gap-3">
                                    @if ($category->icon)
                                        <span class="text-xl">
                                            {{ $category->icon }}
                                        </span>
                                    @endif

                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $category->name }}
                                    </span>
                                </div>

                                <div class="flex gap-3">
                                    <a href="{{ route('categories.edit', $category) }}"
                                       class="text-sm text-blue-600 hover:underline">
                                        Modifier
                                    </a>

                                    <form method="POST"
                                          action="{{ route('categories.destroy', $category) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="text-sm text-red-600 hover:underline">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>

                            </div>

                        @empty
                            <p class="text-gray-500">
                                Aucun revenu enregistré.
                            </p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>