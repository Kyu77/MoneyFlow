<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Modifier une catégorie
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow">

                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                    Modifier {{ $category->name }}
                </h1>

                <form method="POST"
                      action="{{ route('categories.update', $category) }}"
                      class="space-y-5">

                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nom
                        </label>

                        <input id="name"
                               name="name"
                               type="text"
                               value="{{ old('name', $category->name) }}"
                               class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white"
                               required>

                        @error('name')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

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
                                {{ old('type', $category->type) === 'expense' ? 'selected' : '' }}>
                                Dépense
                            </option>

                            <option value="income"
                                {{ old('type', $category->type) === 'income' ? 'selected' : '' }}>
                                Revenu
                            </option>

                        </select>
                    </div>

                    <div>
                        <label for="icon"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Icône
                        </label>

                        <input id="icon"
                               name="icon"
                               type="text"
                               value="{{ old('icon', $category->icon) }}"
                               maxlength="50"
                               class="mt-1 block w-full rounded-lg border-gray-300 dark:bg-gray-700 dark:text-white">

                        @error('icon')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3 pt-4">

                        <a href="{{ route('categories.index') }}"
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