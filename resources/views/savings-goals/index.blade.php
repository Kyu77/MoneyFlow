<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Mes objectifs
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
                        Mes objectifs
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Suivez les projets pour lesquels vous souhaitez mettre de l'argent de côté.
                    </p>
                </div>

                <a
                    href="{{ route('savings-goals.create') }}"
                    class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                >
                    + Ajouter
                </a>

            </div>


            <div class="space-y-4">

                @forelse ($savingsGoals as $goal)

                    @php
                        $savedAmount = $goal->contributions->sum('amount');

                        $percentage = $goal->target_amount > 0
                            ? min(
                                100,
                                ($savedAmount / $goal->target_amount) * 100
                            )
                            : 0;

                        $remainingAmount = max(
                            0,
                            $goal->target_amount - $savedAmount
                        );
                    @endphp


                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

                        <div class="flex justify-between items-start gap-4">

                            <div>

                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    🎯 {{ $goal->name }}
                                </h2>

                                @if ($goal->target_date)
                                    <p class="text-sm text-gray-500 mt-1">
                                        Objectif pour le
                                        {{ $goal->target_date->format('d/m/Y') }}
                                    </p>
                                @endif

                            </div>

                            <div class="flex gap-3">
                                <a
                                   href="{{ route('savings-goals.contributions.create', $goal) }}"
                                    class="inline-block px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700"
                                >
                                    + Ajouter une contribution
                                </a>

                                <a
                                    href="{{ route('savings-goals.edit', $goal) }}"
                                    class="text-sm text-blue-600 hover:underline"
                                >
                                    Modifier
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('savings-goals.destroy', $goal) }}"
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


                        <div class="mt-6">

                            <div class="flex justify-between items-end">

                                <div>

                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ number_format($savedAmount, 2, ',', ' ') }} €
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        sur
                                        {{ number_format($goal->target_amount, 2, ',', ' ') }} €
                                    </p>

                                </div>

                                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                                    {{ number_format($percentage, 0) }} %
                                </p>

                            </div>


                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mt-4">

                                <div
                                    class="bg-blue-600 h-3 rounded-full"
                                    style="width: {{ $percentage }}%"
                                ></div>

                            </div>


                            @if ($remainingAmount > 0)

                                <p class="text-sm text-gray-500 mt-3">
                                    Encore
                                    <strong>
                                        {{ number_format($remainingAmount, 2, ',', ' ') }} €
                                    </strong>
                                    à atteindre.
                                </p>

                            @else

                                <p class="text-sm text-green-600 font-semibold mt-3">
                                    🎉 Objectif atteint !
                                </p>

                            @endif

                        </div>

                    </div>

                @empty

                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

                        <p class="text-gray-600 dark:text-gray-300">
                            Aucun objectif pour le moment.
                        </p>

                        <a
                            href="{{ route('savings-goals.create') }}"
                            class="inline-block mt-4 px-4 py-2 bg-gray-800 text-white rounded-lg"
                        >
                            Créer mon premier objectif
                        </a>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</x-app-layout>