<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-lg bg-gradient-to-r from-blue-50 to-indigo-50">
    <!-- Primary Navigation Menu -->
    <div class="w-full px-4 sm:px-6 lg:px-10 xl:px-12 2xl:px-16">
        <div class="flex justify-between items-center min-h-16">
            <!-- سمت چپ: لوگو + لینک‌های ناوبری -->
            <div class="flex items-center gap-x-3 sm:gap-x-4 lg:gap-x-6">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-x-2 rtl:gap-x-3">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-2 rounded-lg shadow-md">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-gray-800 hidden sm:block">سیستم مدیریت سرمایه‌گذاری خودرو</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center gap-x-1.5 lg:gap-x-2 xl:gap-x-4">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="px-3 py-2 text-sm lg:text-base rounded-lg hover:bg-blue-50 transition whitespace-nowrap">
                        <svg class="h-5 w-5 inline ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        {{ __('داشبورد') }}
                    </x-nav-link>

                    <x-nav-link :href="route('cars.index')" :active="request()->routeIs('cars.*')" class="px-3 py-2 text-sm lg:text-base rounded-lg hover:bg-blue-50 transition whitespace-nowrap">
                        <svg class="h-5 w-5 inline ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        {{ __('خودروها') }}
                    </x-nav-link>

                    <x-nav-link :href="route('investors.index')" :active="request()->routeIs('investors.*')" class="px-3 py-2 text-sm lg:text-base rounded-lg hover:bg-blue-50 transition whitespace-nowrap">
                        <svg class="h-5 w-5 inline ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ __('سرمایه‌گذاران') }}
                    </x-nav-link>

                    <x-nav-link :href="route('investments.index')" :active="request()->routeIs('investments.*')" class="px-3 py-2 text-sm lg:text-base rounded-lg hover:bg-blue-50 transition whitespace-nowrap">
                        <svg class="h-5 w-5 inline ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ __('سرمایه‌گذاری‌ها') }}
                    </x-nav-link>

                    @can('view users')
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="px-3 py-2 text-sm lg:text-base rounded-lg hover:bg-red-50 transition whitespace-nowrap">
                            <svg class="h-5 w-5 inline ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            {{ __('مدیریت کاربران') }}
                        </x-nav-link>
                    @endcan

                    <!-- منوی کشویی مدیریت مالی -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-x-1.5 px-3 py-2 text-sm lg:text-base rounded-lg hover:bg-blue-50 transition whitespace-nowrap text-gray-700 hover:text-gray-900">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            {{ __('مدیریت مالی') }}
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-52 bg-white rounded-lg shadow-xl py-2 z-50 border border-gray-200">
                            <a href="{{ route('assets.index') }}" class="block px-4 py-2.5 text-gray-800 hover:bg-blue-50 transition flex items-center gap-x-2">
                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('دارایی‌ها') }}
                            </a>
                            <a href="{{ route('liabilities.index') }}" class="block px-4 py-2.5 text-gray-800 hover:bg-blue-50 transition flex items-center gap-x-2">
                                <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ __('تعهدات') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- سمت راست: پروفایل / خروج -->
            <div class="hidden md:flex items-center gap-x-4 lg:gap-x-6">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-x-2.5 bg-white px-3 py-2 rounded-lg shadow-sm hover:shadow transition border border-gray-200">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                            {{ substr(Auth::user()->name ?? '', 0, 1) }}
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-gray-700">{{ Auth::user()->name ?? 'کاربر' }}</div>
                            <div class="text-xs text-gray-500">{{ Auth::user()->email ?? '' }}</div>
                        </div>
                        <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-xl py-2 z-50 border border-gray-200">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2.5 text-gray-800 hover:bg-blue-50 transition flex items-center gap-x-2.5">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ __('پروفایل') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-right px-4 py-2.5 text-red-600 hover:bg-red-50 transition flex items-center gap-x-2.5">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                {{ __('خروج') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hamburger (موبایل) -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu (موبایل) -->
    <div :class="{'block': open, 'hidden': ! open}" class="md:hidden border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                {{ __('داشبورد') }}
            </x-responsive-nav-link>

            <!-- بقیه لینک‌های موبایل رو هم مثل قبل کپی کن -->

            <!-- مدیریت مالی در موبایل -->
            <x-responsive-nav-link :href="route('assets.index')" :active="request()->routeIs('assets.*')">
                <svg class="h-5 w-5 inline ml-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ __('دارایی‌ها') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('liabilities.index')" :active="request()->routeIs('liabilities.*')">
                <svg class="h-5 w-5 inline ml-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ __('تعهدات') }}
            </x-responsive-nav-link>
        </div>

        <!-- تنظیمات موبایل -->
        <div class="pt-4 pb-3 border-t border-gray-200 bg-gray-50 px-4">
            <div class="px-3">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name ?? 'کاربر' }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email ?? '' }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    {{ __('پروفایل') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                        <svg class="h-5 w-5 inline ml-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        {{ __('خروج') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    [x-cloak] { display: none !important; }
    .transition { transition: all 0.2s ease; }
</style>