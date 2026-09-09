<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Modifier le compte
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <form method="POST"
                  action="{{ route('accounts.update', $account) }}"
                  class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow space-y-6">

                @csrf
                @method('PUT')

                {{-- Nom --}}
                <div>
                    <label for="name"
                           class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                        Nom du compte
                    </label>

                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $account->name) }}"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white"
                    >

                    @error('name')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Type --}}
                <div>
                    <label for="type"
                           class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                        Type
                    </label>

                    <select
                        id="type"
                        name="type"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:bg-gray-700 dark:text-white"
                    >
                        <option value="current"
                            @selected(old('type', $account->type) === 'current')}>
                            Compte courant
                        </option>

                        <option value="savings"
                            @selected(old('type', $account->type) === 'savings')}>
                            Épargne
                        </option>

                        <option value="joint"
                            @selected(old('type', $account->type) === 'joint')}>
                            Compte joint
                        </option>

                        <option value="other"
                            @selected(old('type', $account->type) === 'other')}>
                            Autre
                        </option>
                    </select>

                    @error('type')
                        <p class="mt-1 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                

                {{-- Compte partagé --}}
                <div>
                    <label class="inline-flex items-center">
                        <input
                            type="checkbox"
                            name="is_shared"
                            value="1"
                            @checked(old('is_shared', $account->is_shared))
                            class="rounded border-gray-300"
                        >

                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            Compte partagé
                        </span>
                    </label>
                </div>

                {{-- Actions --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('accounts.index') }}"
                       class="px-4 py-2 rounded-lg border">
                        Annuler
                    </a>

                    <button
                        type="submit"
                        class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                    >
                        Enregistrer les modifications
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>