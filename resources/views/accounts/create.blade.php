<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ajouter un compte
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <form method="POST" action="{{ route('accounts.store') }}"
                  class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow space-y-6">

                @csrf

                <div>
                    <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                        Nom du compte
                    </label>

                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white"
                        placeholder="Compte courant"
                    >

                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                        Type
                    </label>

                    <select
                        id="type"
                        name="type"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white"
                    >
                        <option value="current">Compte courant</option>
                        <option value="savings">Épargne</option>
                        <option value="joint">Compte joint</option>
                        <option value="other">Autre</option>
                    </select>
                </div>

                <div>
                    <label for="balance" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                        Solde actuel
                    </label>

                    <input
                        id="balance"
                        name="balance"
                        type="number"
                        step="0.01"
                        min="0"
                        value="{{ old('balance', 0) }}"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white"
                    >

                    @error('balance')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="inline-flex items-center">
                        <input
                            type="checkbox"
                            name="is_shared"
                            value="1"
                            class="rounded border-gray-300"
                        >

                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            Compte partagé
                        </span>
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('accounts.index') }}"
                       class="px-4 py-2 rounded-lg border">
                        Annuler
                    </a>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                    >
                        Créer le compte
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>