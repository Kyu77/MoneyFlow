<nav
    x-data="{ open: false }"
    class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700"
>

    {{-- ========================================================= --}}
    {{-- DESKTOP NAVIGATION                                        --}}
    {{-- ========================================================= --}}

    <div class="hidden sm:block">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex justify-between h-16">

                <div class="flex">

                    {{-- Logo --}}

                    <div class="shrink-0 flex items-center">

                        <a href="{{ route('dashboard') }}">

                            <x-application-logo
                                class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
                            />

                        </a>

                    </div>


                    {{-- Navigation --}}

                    <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">

                        <x-nav-link
                            :href="route('dashboard')"
                            :active="request()->routeIs('dashboard')"
                        >
                            Accueil
                        </x-nav-link>


                        <x-nav-link
                            :href="route('accounts.index')"
                            :active="request()->routeIs('accounts.*')"
                        >
                            Comptes
                        </x-nav-link>


                        <x-nav-link
                            :href="route('savings-goals.index')"
                            :active="request()->routeIs('savings-goals.*')"
                        >
                            Objectifs
                        </x-nav-link>


                        <x-nav-link
                            :href="route('recurring-transactions.index')"
                            :active="request()->routeIs('recurring-transactions.*')"
                        >
                            Récurrences
                        </x-nav-link>

                    </div>

                </div>


                {{-- User menu --}}

                <div class="hidden sm:flex sm:items-center sm:ms-6">

                    <x-dropdown align="right" width="48">

                        <x-slot name="trigger">

                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition"
                            >

                                <div>
                                    {{ Auth::user()->name }}
                                </div>

                                <div class="ms-1">

                                    <svg
                                        class="fill-current h-4 w-4"
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                    >

                                        <path
                                            fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />

                                    </svg>

                                </div>

                            </button>

                        </x-slot>


                        <x-slot name="content">

                            <x-dropdown-link :href="route('profile.edit')">
                                Profil
                            </x-dropdown-link>


                            <form method="POST" action="{{ route('logout') }}">

                                @csrf

                                <x-dropdown-link
                                    :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                >
                                    Déconnexion
                                </x-dropdown-link>

                            </form>

                        </x-slot>

                    </x-dropdown>

                </div>

            </div>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- MOBILE APP HEADER                                         --}}
    {{-- ========================================================= --}}

    <div class="sm:hidden">

        <div class="h-14 px-4 flex items-center justify-between">

            <a
                href="{{ route('dashboard') }}"
                class="flex items-center gap-2"
            >

                <x-application-logo
                    class="block h-8 w-auto fill-current text-gray-800 dark:text-gray-200"
                />

            </a>


            {{-- Avatar --}}

            <a
                href="{{ route('profile.edit') }}"
                class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-700 dark:text-gray-200"
                aria-label="Profil"
            >

                <span class="text-sm font-bold">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>

            </a>

        </div>

    </div>



    {{-- ========================================================= --}}
    {{-- MOBILE BOTTOM NAVIGATION                                  --}}
    {{-- ========================================================= --}}

    <div
        class="sm:hidden fixed bottom-0 inset-x-0 z-50 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-t border-gray-200 dark:border-gray-700 pb-[env(safe-area-inset-bottom)]"
    >

        <div class="h-16 grid grid-cols-5 items-center px-2">


            {{-- Accueil --}}

            <a
                href="{{ route('dashboard') }}"
                class="h-14 flex flex-col items-center justify-center rounded-xl transition
                    {{ request()->routeIs('dashboard')
                        ? 'text-gray-900 dark:text-white'
                        : 'text-gray-400 dark:text-gray-500' }}"
            >

                <span class="text-xl leading-none">
                    🏠
                </span>

                <span class="text-[10px] font-medium mt-1">
                    Accueil
                </span>

            </a>



            {{-- Comptes --}}

            <a
                href="{{ route('accounts.index') }}"
                class="h-14 flex flex-col items-center justify-center rounded-xl transition
                    {{ request()->routeIs('accounts.*')
                        ? 'text-gray-900 dark:text-white'
                        : 'text-gray-400 dark:text-gray-500' }}"
            >

                <span class="text-xl leading-none">
                    💳
                </span>

                <span class="text-[10px] font-medium mt-1">
                    Comptes
                </span>

            </a>



            {{-- Ajouter --}}

            <div class="flex justify-center">

                <a
                    href="{{ route('transactions.create') }}"
                    class="w-14 h-14 -mt-7 rounded-full bg-gray-900 dark:bg-white text-white dark:text-gray-900 flex items-center justify-center shadow-xl border-4 border-white dark:border-gray-900 active:scale-95 transition"
                    aria-label="Ajouter une transaction"
                >

                    <span class="text-3xl font-light leading-none">
                        +
                    </span>

                </a>

            </div>



            {{-- Objectifs --}}

            <a
                href="{{ route('savings-goals.index') }}"
                class="h-14 flex flex-col items-center justify-center rounded-xl transition
                    {{ request()->routeIs('savings-goals.*')
                        ? 'text-gray-900 dark:text-white'
                        : 'text-gray-400 dark:text-gray-500' }}"
            >

                <span class="text-xl leading-none">
                    🎯
                </span>

                <span class="text-[10px] font-medium mt-1">
                    Objectifs
                </span>

            </a>



            {{-- Plus --}}

            <button
                type="button"
                @click="open = !open"
                class="h-14 flex flex-col items-center justify-center rounded-xl text-gray-400 dark:text-gray-500"
            >

                <span class="text-xl leading-none">
                    ⋯
                </span>

                <span class="text-[10px] font-medium mt-1">
                    Plus
                </span>

            </button>

        </div>



        {{-- ===================================================== --}}
        {{-- MENU PLUS                                             --}}
        {{-- ===================================================== --}}

        <div
            x-show="open"
            x-transition
            @click.outside="open = false"
            class="absolute bottom-[calc(4rem+env(safe-area-inset-bottom))] right-2 w-64 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden"
        >


            {{-- ================================================= --}}
            {{-- APPARENCE                                          --}}
            {{-- ================================================= --}}

            <div class="px-4 py-4 border-b border-gray-200 dark:border-gray-700">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-3">
                    Apparence
                </p>


                <div class="grid grid-cols-3 gap-2">


                    {{-- Clair --}}

                    <button
                        type="button"
                        onclick="setMoneyFlowTheme('light')"
                        class="min-h-[58px] rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-medium hover:bg-gray-200 dark:hover:bg-gray-600 active:scale-[0.97] transition"
                    >

                        <span class="text-lg">
                            ☀️
                        </span>

                        <span class="block mt-1">
                            Clair
                        </span>

                    </button>



                    {{-- Sombre --}}

                    <button
                        type="button"
                        onclick="setMoneyFlowTheme('dark')"
                        class="min-h-[58px] rounded-xl bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 text-xs font-medium hover:bg-gray-200 dark:hover:bg-gray-600 active:scale-[0.97] transition"
                    >

                        <span class="text-lg">
                            🌙
                        </span>

                        <span class="block mt-1">
                            Sombre
                        </span>

                    </button>

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- RÉCURRENCES                                        --}}
            {{-- ================================================= --}}

            <a
                href="{{ route('recurring-transactions.index') }}"
                class="flex items-center gap-3 px-5 py-4 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
            >

                <span class="text-lg">
                    🔄
                </span>

                <span>
                    Récurrences
                </span>

            </a>



            {{-- ================================================= --}}
            {{-- VIREMENT                                           --}}
            {{-- ================================================= --}}

            <a
                href="{{ route('transfers.create') }}"
                class="flex items-center gap-3 px-5 py-4 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
            >

                <span class="text-lg">
                    ↔️
                </span>

                <span>
                    Virement
                </span>

            </a>



            {{-- ================================================= --}}
            {{-- PROFIL                                             --}}
            {{-- ================================================= --}}

            <a
                href="{{ route('profile.edit') }}"
                class="flex items-center gap-3 px-5 py-4 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
            >

                <span class="text-lg">
                    👤
                </span>

                <span>
                    Profil
                </span>

            </a>



            {{-- Séparation --}}

            <div class="border-t border-gray-200 dark:border-gray-700"></div>



            {{-- ================================================= --}}
            {{-- DÉCONNEXION                                        --}}
            {{-- ================================================= --}}

            <form method="POST" action="{{ route('logout') }}">

                @csrf

                <button
                    type="submit"
                    class="w-full flex items-center gap-3 px-5 py-4 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 text-left transition"
                >

                    <span class="text-lg">
                        ↪️
                    </span>

                    <span>
                        Déconnexion
                    </span>

                </button>

            </form>

        </div>

    </div>

</nav>



{{-- ============================================================= --}}
{{-- MONEYFLOW THEME                                               --}}
{{-- ============================================================= --}}

<script>

        function setMoneyFlowTheme(theme) {

            localStorage.setItem('moneyflow-theme', theme);

            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }

        const prefersDark = window.matchMedia(
            '(prefers-color-scheme: dark)'
        ).matches;


        document.documentElement.classList.toggle(
            'dark',
            prefersDark
        );

    

</script>