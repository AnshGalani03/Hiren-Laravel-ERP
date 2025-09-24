<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex align-items-center">

                    <!-- Dashboard -->
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <!-- People Management Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none {{ request()->routeIs('dealers.*', 'employees.*', 'customers.*') ? 'border-indigo-400 text-gray-900 focus:border-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300' }}">
                            People Management
                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                            style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('dealers.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dealers.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                    Dealers
                                </a>
                                <a href="{{ route('employees.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('employees.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                    Employees
                                </a>
                                <a href="{{ route('customers.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('customers.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                    Customers
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Projects -->
                    <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">
                        {{ __('Projects') }}
                    </x-nav-link>

                    <!-- Contracts & Tenders Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none {{ request()->routeIs('tenders.*', 'sub-contractors.*') ? 'border-indigo-400 text-gray-900 focus:border-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300' }}">
                            Contracts & Tenders
                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                            style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('tenders.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('tenders.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                    Tenders
                                </a>
                                <a href="{{ route('sub-contractors.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('sub-contractors.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                    Sub-contractors
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Invoices & Bills Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none {{ request()->routeIs('invoices.*', 'bills.*') ? 'border-indigo-400 text-gray-900 focus:border-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300' }}">
                            Invoices & Bills
                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                            style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('invoices.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('invoices.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                    Dealer Invoices
                                </a>
                                <a href="{{ route('bills.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('bills.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                    Bills
                                </a>
                                <a href="{{ route('ra-bills.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('ra-bills.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                    RA Bills
                                </a>
                                <a href="{{ route('exports.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('exports.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                    Export Reports
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Transactions -->
                    <x-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')">
                        {{ __('Transactions') }}
                    </x-nav-link>

                    <!-- Master Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none {{ request()->routeIs('outgoings.*', 'incomings.*', 'products.*') ? 'border-indigo-400 text-gray-900 focus:border-indigo-700' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300' }}">
                            Master
                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute z-50 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                            style="display: none;">
                            <div class="py-1">
                                <a href="{{ route('outgoings.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('outgoings.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                    Outgoings
                                </a>
                                <a href="{{ route('incomings.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('incomings.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                    Incomings
                                </a>
                                <a href="{{ route('products.index') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('products.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                    Products
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <!-- Dashboard -->
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <!-- People Management Mobile -->
            <div x-data="{ mobileOpen1: false }">
                <button @click="mobileOpen1 = !mobileOpen1" class="w-full flex items-center justify-between py-2 pe-4 ps-3 text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 focus:outline-none focus:text-gray-800 focus:bg-gray-50 transition duration-150 ease-in-out">
                    People Management
                    <svg class="h-4 w-4 transform transition-transform" :class="mobileOpen1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="mobileOpen1" class="bg-gray-50" style="display: none;">
                    <x-responsive-nav-link :href="route('dealers.index')" :active="request()->routeIs('dealers.*')" class="pl-8">
                        {{ __('Dealers') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')" class="pl-8">
                        {{ __('Employees') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('customers.index')" :active="request()->routeIs('customers.*')" class="pl-8">
                        {{ __('Customers') }}
                    </x-responsive-nav-link>
                </div>
            </div>

            <!-- Projects Mobile -->
            <x-responsive-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">
                {{ __('Projects') }}
            </x-responsive-nav-link>

            <!-- Contracts & Tenders Mobile -->
            <div x-data="{ mobileOpen2: false }">
                <button @click="mobileOpen2 = !mobileOpen2" class="w-full flex items-center justify-between py-2 pe-4 ps-3 text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 focus:outline-none focus:text-gray-800 focus:bg-gray-50 transition duration-150 ease-in-out">
                    Contracts & Tenders
                    <svg class="h-4 w-4 transform transition-transform" :class="mobileOpen2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="mobileOpen2" class="bg-gray-50" style="display: none;">
                    <x-responsive-nav-link :href="route('tenders.index')" :active="request()->routeIs('tenders.*')" class="pl-8">
                        {{ __('Tenders') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('sub-contractors.index')" :active="request()->routeIs('sub-contractors.*')" class="pl-8">
                        {{ __('Sub-contractors') }}
                    </x-responsive-nav-link>
                </div>
            </div>

            <!-- Invoices & Bills Mobile -->
            <div x-data="{ mobileOpen3: false }">
                <button @click="mobileOpen3 = !mobileOpen3" class="w-full flex items-center justify-between py-2 pe-4 ps-3 text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 focus:outline-none focus:text-gray-800 focus:bg-gray-50 transition duration-150 ease-in-out">
                    Invoices & Bills
                    <svg class="h-4 w-4 transform transition-transform" :class="mobileOpen3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="mobileOpen3" class="bg-gray-50" style="display: none;">
                    <x-responsive-nav-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')" class="pl-8">
                        {{ __('Dealer Invoices') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('bills.index')" :active="request()->routeIs('bills.*')" class="pl-8">
                        {{ __('Bills') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('ra-bills.index')" :active="request()->routeIs('ra-bills.*')" class="pl-8">
                        {{ __('RA Bills') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('exports.index')" :active="request()->routeIs('exports.*')" class="pl-8">
                        {{ __('Export Reports') }}
                    </x-responsive-nav-link>
                </div>
            </div>

            <!-- Transactions Mobile -->
            <x-responsive-nav-link :href="route('transactions.index')" :active="request()->routeIs('transactions.*')">
                {{ __('Transactions') }}
            </x-responsive-nav-link>

            <!-- Master Mobile -->
            <div x-data="{ mobileOpen4: false }">
                <button @click="mobileOpen4 = !mobileOpen4" class="w-full flex items-center justify-between py-2 pe-4 ps-3 text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 focus:outline-none focus:text-gray-800 focus:bg-gray-50 transition duration-150 ease-in-out">
                    Master
                    <svg class="h-4 w-4 transform transition-transform" :class="mobileOpen4 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="mobileOpen4" class="bg-gray-50" style="display: none;">
                    <x-responsive-nav-link :href="route('outgoings.index')" :active="request()->routeIs('outgoings.*')" class="pl-8">
                        {{ __('Outgoings') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('incomings.index')" :active="request()->routeIs('incomings.*')" class="pl-8">
                        {{ __('Incomings') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="pl-8">
                        {{ __('Products') }}
                    </x-responsive-nav-link>
                </div>
            </div>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>