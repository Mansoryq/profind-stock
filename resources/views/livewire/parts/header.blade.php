<header class="bg-white sticky top-0 z-50 transition duration-500 ease-in-out border-b border-gray-200"
    x-data="{ isScrolling: false, mobileMenuOpen: false }" @scroll.window="isScrolling = (window.pageYOffset > 0)"
    :class="{ 'bg-white/u': isScrolling }">
    <div class="max-w-7xl mx-auto px-4 py-4 md:py-5 lg:py-6">
        <div class="flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center">
                <span class="text-lg md:text-xl font-semibold text-gray-900">
                    <span class="text-blue-500">Prof</span>Gid<span class="text-blue-500">.</span>
                </span>
            </a>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button type="button"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    :aria-expanded="mobileMenuOpen">
                    <span class="sr-only">Открыть меню</span>
                    <!-- Hamburger icon -->
                    <svg class="h-6 w-6" x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Close icon -->
                    <svg class="h-6 w-6" x-show="mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center space-x-6 lg:space-x-8">
                <a href="{{ route('home') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-blue-600 font-semibold' : 'text-gray-500' }} hover:text-blue-600 transition-colors">
                    {{ __('messages.nav_how_it_works') }}
                </a>
                <a href="{{ route('about') }}" class="text-sm font-medium {{ request()->routeIs('about') ? 'text-blue-600 font-semibold' : 'text-gray-500' }} hover:text-blue-600 transition-colors">
                    {{ __('all.header.about') }}
                </a>
                <a href="{{ route('profession-map') }}"
                    class="text-sm font-medium {{ request()->routeIs('profession-map') ? 'text-blue-600 font-semibold' : 'text-gray-500' }} hover:text-blue-600 transition-colors">
                    {{ __('all.header.professions') }}
                </a>
                <a href="{{ route('grant-analysis') }}"
                    class="text-sm font-medium {{ request()->routeIs('grant-analysis') ? 'text-blue-600 font-semibold' : 'text-gray-500' }} hover:text-blue-600 transition-colors">
                    {{ __('all.header.grant') }}
                </a>
                <a href="{{ route('test-preparation') }}"
                    class="text-sm font-medium {{ request()->routeIs(['test-preparation', 'test', 'talent-test', 'test.results', 'talent-test-results']) ? 'text-blue-600 font-semibold' : 'text-gray-500' }} hover:text-blue-600 transition-colors">
                    {{ __('messages.nav_take_test') }}
                </a>
            </nav>

            <!-- Desktop Auth/Actions -->
            <div class="hidden md:flex items-center space-x-4">
                 <div class="relative inline-block text-left" x-data="{ open: false }">
                    <div>
                        <button type="button"
                            class="flex items-center gap-x-2 text-sm font-medium text-gray-700 hover:text-gray-900 focus:outline-none"
                            @click="open = !open" @click.away="open = false">
                            {{ strtoupper(session()->get('locale') ?? 'ru') }}
                            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <div x-show="open" @click.away="open = false"
                        class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95">
                        <div class="py-1">
                            <a href="{{ route('locale.set', 'kk') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ session()->get('locale') == 'kk' ? 'bg-gray-100 font-medium' : '' }}">
                                {{ __('messages.language_kk') }}
                            </a>
                            <a href="{{ route('locale.set', 'ru') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ session()->get('locale') == 'ru' ? 'bg-gray-100 font-medium' : '' }}">
                                {{ __('messages.language_ru') }}
                            </a>
                        </div>
                    </div>
                </div>

                @auth
                    <!-- User Dropdown -->
                    @livewire('parts.user-dropdown')
                @else
                    <!-- Login/Register Buttons -->
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('login') }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                            {{ __('messages.login') }}
                        </a>
                        <span class="text-gray-300">/</span>
                        <a href="{{ route('register') }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors">
                            {{ __('messages.register') }}
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden"
             x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <div class="pt-2 pb-3 space-y-1 border-t border-gray-200 mt-4">
                <!-- Mobile navigation links -->
                <a href="{{ route('home') }}"
                   class="block px-3 py-2 text-base font-medium {{ request()->routeIs('home') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-gray-700' }} hover:text-blue-600 hover:bg-gray-50 rounded-md transition-colors">
                    {{ __('messages.nav_how_it_works') }}
                </a>
                <a href="{{ route('about') }}"
                   class="block px-3 py-2 text-base font-medium {{ request()->routeIs('about') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-gray-700' }} hover:text-blue-600 hover:bg-gray-50 rounded-md transition-colors">
                    {{ __('all.header.about') }}
                </a>
                <a href="{{ route('profession-map') }}"
                   class="block px-3 py-2 text-base font-medium {{ request()->routeIs('profession-map') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-gray-700' }} hover:text-blue-600 hover:bg-gray-50 rounded-md transition-colors">
                    {{ __('all.header.professions') }}
                </a>

                <a href="{{ route('grant-analysis') }}"
                   class="block px-3 py-2 text-base font-medium {{ request()->routeIs('grant-analysis') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-gray-700' }} hover:text-blue-600 hover:bg-gray-50 rounded-md transition-colors">
                    {{ __('all.header.grant') }}
                </a>

                <a href="{{ route('test-preparation') }}"
                   class="block px-3 py-2 text-base font-medium {{ request()->routeIs(['test-preparation', 'test', 'talent-test', 'test.results', 'talent-test-results']) ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-gray-700' }} hover:text-blue-600 hover:bg-gray-50 rounded-md transition-colors">
                    {{ __('messages.nav_take_test') }}
                </a>

                <div class="border-t border-gray-200 pt-3 mt-3">
                    <div class="px-3 py-2">
                        <div class="flex gap-2">
                            <a href="{{ route('locale.set', 'kk') }}"
                               class="flex-1 px-4 py-2 text-center text-sm font-medium rounded-md transition-colors {{ session()->get('locale') == 'kk' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                {{ __('messages.language_kk') }}
                            </a>
                            <a href="{{ route('locale.set', 'ru') }}"
                               class="flex-1 px-4 py-2 text-center text-sm font-medium rounded-md transition-colors {{ session()->get('locale') == 'ru' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                {{ __('messages.language_ru') }}
                            </a>
                        </div>
                    </div>
                </div>

                @auth
                    <!-- Mobile user menu -->
                    <div class="border-t border-gray-200 pt-4 pb-3">
                        <div class="flex items-center px-3">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                                    <span class="text-white text-sm font-medium">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-gray-800">{{ auth()->user()->name }}</div>
                                <div class="text-sm font-medium text-gray-500">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <a href="{{ route('profile') }}"
                               class="block px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md transition-colors">
                                {{ __('messages.profile') }}
                            </a>
                            <a href="{{ route('test.history') }}"
                                class="block px-3 py-2 text-base font-medium {{ request()->routeIs('test.history') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-gray-700' }} hover:text-blue-600 hover:bg-gray-50 rounded-md transition-colors">
                                {{ __('messages.test_history') }}
                            </a>
                            <a href="{{ route('my-professions') }}"
                               class="block px-3 py-2 text-base font-medium {{ request()->routeIs('my-professions') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-blue-600' }} hover:text-blue-600 hover:bg-gray-50 rounded-md transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    {{ __('all.header.my_professions') }}
                                    @if($userProfessionsCount > 0)
                                        <span class="ml-auto bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded-full">
                                            {{ $userProfessionsCount }}
                                        </span>
                                    @endif
                                </div>
                            </a>
                            <a href="{{ route('my-spheres') }}"
                               class="block px-3 py-2 text-base font-medium {{ request()->routeIs('my-spheres') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-blue-600' }} hover:text-blue-600 hover:bg-gray-50 rounded-md transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                    {{ __('all.header.my_spheres') }}
                                    @if($userSpheresCount > 0)
                                        <span class="ml-auto bg-blue-100 text-blue-800 text-xs font-medium px-2 py-0.5 rounded-full">
                                            {{ $userSpheresCount }}
                                        </span>
                                    @endif
                                </div>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="block w-full text-left px-3 py-2 text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50 rounded-md transition-colors">
                                    {{ __('messages.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Mobile auth buttons -->
                    <div class="border-t border-gray-200 pt-4 pb-3">
                        <div class="space-y-2 px-3">
                            <a href="{{ route('login') }}"
                               class="block w-full px-4 py-2 text-center text-blue-600 hover:text-blue-800 border border-blue-600 hover:border-blue-800 rounded-md font-medium transition-colors">
                                {{ __('messages.login') }}
                            </a>
                            <a href="{{ route('register') }}"
                               class="block w-full px-4 py-2 text-center text-white bg-blue-600 hover:bg-blue-700 rounded-md font-medium transition-colors">
                                {{ __('messages.register') }}
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</header>
