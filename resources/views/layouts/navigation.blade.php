<nav x-data="{ open: false, financialOpen: false }" class="bg-white border-b border-gray-100 shadow-lg bg-gradient-to-r from-blue-50 to-indigo-50 sticky top-0 z-50" x-cloak>
    <!-- کانتینر اصلی با پدینگ هوشمند -->
    <div class="w-full px-3 sm:px-4 md:px-5 lg:px-6 xl:px-8 2xl:px-12 mx-auto">
        <div class="flex justify-between items-center min-h-[60px] sm:min-h-[64px] md:min-h-[68px] lg:min-h-[72px]">
            
            <!-- بخش سمت چپ: لوگو و منو -->
            <div class="flex items-center gap-1 sm:gap-2 md:gap-3 lg:gap-4 xl:gap-5 flex-1 md:flex-none">
                <!-- لوگو -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 sm:gap-2 rtl:gap-x-2 sm:rtl:gap-x-3">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-1.5 sm:p-2 md:p-2.5 rounded-lg shadow-md">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6 md:h-7 md:w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-sm sm:text-base md:text-lg font-bold text-gray-800 hidden xs:block">مدیریت سرمایه‌گذاری</span>
                        <span class="text-xs sm:text-sm md:text-base text-gray-600 hidden xs:block">خودرو</span>
                    </a>
                </div>

                <!-- دسکتاپ منو (md به بالا) -->
                <div class="hidden md:flex items-center gap-0.5 lg:gap-1 xl:gap-1.5">
                    <!-- داشبورد اصلی -->
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                        class="px-2 lg:px-2.5 xl:px-3 py-1.5 lg:py-2 text-xs lg:text-sm xl:text-base rounded-lg hover:bg-blue-50 transition whitespace-nowrap">
                        <svg class="h-3.5 w-3.5 lg:h-4 lg:w-4 xl:h-4.5 xl:w-4.5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        <span class="hidden lg:inline">داشبورد</span>
                        <span class="lg:hidden">داشبورد</span>
                    </x-nav-link>

                    <!-- داشبورد شخصی -->
                    <x-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')" 
                        class="px-2 lg:px-2.5 xl:px-3 py-1.5 lg:py-2 text-xs lg:text-sm xl:text-base rounded-lg hover:bg-green-50 transition whitespace-nowrap">
                        <svg class="h-3.5 w-3.5 lg:h-4 lg:w-4 xl:h-4.5 xl:w-4.5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="hidden lg:inline">شخصی</span>
                        <span class="lg:hidden">شخصی</span>
                    </x-nav-link>

                    <!-- خودروها -->
                    @can('view cars')
                        <x-nav-link :href="route('cars.index')" :active="request()->routeIs('cars.*')" 
                            class="px-2 lg:px-2.5 xl:px-3 py-1.5 lg:py-2 text-xs lg:text-sm xl:text-base rounded-lg hover:bg-blue-50 transition whitespace-nowrap">
                            <svg class="h-3.5 w-3.5 lg:h-4 lg:w-4 xl:h-4.5 xl:w-4.5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                            <span class="hidden lg:inline">خودروها</span>
                            <span class="lg:hidden">خودرو</span>
                        </x-nav-link>
                    @endcan

                    <!-- سرمایه‌گذاران -->
                    @can('view investors')
                        <x-nav-link :href="route('investors.index')" :active="request()->routeIs('investors.*')" 
                            class="px-2 lg:px-2.5 xl:px-3 py-1.5 lg:py-2 text-xs lg:text-sm xl:text-base rounded-lg hover:bg-blue-50 transition whitespace-nowrap">
                            <svg class="h-3.5 w-3.5 lg:h-4 lg:w-4 xl:h-4.5 xl:w-4.5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="hidden lg:inline">سرمایه‌گذاران</span>
                            <span class="lg:hidden">سرمایه</span>
                        </x-nav-link>
                    @endcan

                    <!-- اشخاص -->
                    @can('view people')
                        <x-nav-link :href="route('people.index')" :active="request()->routeIs('people.*')" 
                            class="px-2 lg:px-2.5 xl:px-3 py-1.5 lg:py-2 text-xs lg:text-sm xl:text-base rounded-lg hover:bg-teal-50 transition whitespace-nowrap">
                            <svg class="h-3.5 w-3.5 lg:h-4 lg:w-4 xl:h-4.5 xl:w-4.5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span class="hidden lg:inline">اشخاص</span>
                            <span class="lg:hidden">اشخاص</span>
                        </x-nav-link>
                    @endcan

                    <!-- سرمایه‌گذاری‌ها -->
                    @can('view investments')
                        <x-nav-link :href="route('investments.index')" :active="request()->routeIs('investments.*')" 
                            class="px-2 lg:px-2.5 xl:px-3 py-1.5 lg:py-2 text-xs lg:text-sm xl:text-base rounded-lg hover:bg-blue-50 transition whitespace-nowrap">
                            <svg class="h-3.5 w-3.5 lg:h-4 lg:w-4 xl:h-4.5 xl:w-4.5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="hidden lg:inline">سرمایه‌گذاری</span>
                            <span class="lg:hidden">سرمایه</span>
                        </x-nav-link>
                    @endcan

                    <!-- فروش‌ها -->
                    @can('view sales')
                        <x-nav-link :href="route('car-sales.index')" :active="request()->routeIs('car-sales.*')" 
                            class="px-2 lg:px-2.5 xl:px-3 py-1.5 lg:py-2 text-xs lg:text-sm xl:text-base rounded-lg hover:bg-blue-50 transition whitespace-nowrap">
                            <svg class="h-3.5 w-3.5 lg:h-4 lg:w-4 xl:h-4.5 xl:w-4.5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="hidden lg:inline">فروش‌ها</span>
                            <span class="lg:hidden">فروش</span>
                        </x-nav-link>
                    @endcan

                    <!-- مدیریت کاربران -->
                    @can('view users')
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" 
                            class="px-2 lg:px-2.5 xl:px-3 py-1.5 lg:py-2 text-xs lg:text-sm xl:text-base rounded-lg hover:bg-red-50 transition whitespace-nowrap font-medium">
                            <svg class="h-3.5 w-3.5 lg:h-4 lg:w-4 xl:h-4.5 xl:w-4.5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span class="hidden lg:inline">مدیریت کاربران</span>
                            <span class="lg:hidden">کاربران</span>
                        </x-nav-link>
                    @endcan

<!-- منوی کشویی مدیریت مالی (تبلت به بالا) -->
@if(auth()->user()->can('view assets') || auth()->user()->can('view liabilities') || auth()->user()->can('view expenses') || auth()->user()->can('view receivables'))
    <div class="relative" x-data="{ financialOpen: false }">
        <button @click="financialOpen = !financialOpen" @click.away="financialOpen = false" 
            class="flex items-center gap-1 px-2 lg:px-2.5 xl:px-3 py-1.5 lg:py-2 text-xs lg:text-sm xl:text-base rounded-lg hover:bg-blue-50 transition whitespace-nowrap text-gray-700 hover:text-gray-900">
            <svg class="h-3.5 w-3.5 lg:h-4 lg:w-4 xl:h-4.5 xl:w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span class="hidden lg:inline">مدیریت مالی</span>
            <span class="lg:hidden">مالی</span>
            <svg class="h-3 w-3 lg:h-3.5 lg:w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>
        
        <!-- منوی کشویی مالی -->
        <div x-show="financialOpen" @click.away="financialOpen = false" 
            class="absolute right-0 mt-2 w-44 lg:w-48 bg-white rounded-lg shadow-xl py-1.5 z-50 border border-gray-200 text-xs lg:text-sm">
            
            @can('view assets')
                <a href="{{ route('assets.index') }}" class="block px-3 lg:px-4 py-2 text-gray-700 hover:bg-blue-50 transition flex items-center gap-2">
                    <svg class="h-4 w-4 lg:h-5 lg:w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    دارایی‌ها
                </a>
            @endcan
            
            @can('view liabilities')
                <a href="{{ route('liabilities.index') }}" class="block px-3 lg:px-4 py-2 text-gray-700 hover:bg-blue-50 transition flex items-center gap-2">
                    <svg class="h-4 w-4 lg:h-5 lg:w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    تعهدات
                </a>
            @endcan

            @can('view expenses')
                <a href="{{ route('expenses.index') }}" class="block px-3 lg:px-4 py-2 text-gray-700 hover:bg-orange-50 transition flex items-center gap-2">
                    <svg class="h-4 w-4 lg:h-5 lg:w-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    هزینه‌ها
                </a>
            @endcan

            @can('view receivables')
                <a href="{{ route('receivables.index') }}" class="block px-3 lg:px-4 py-2 text-gray-700 hover:bg-purple-50 transition flex items-center gap-2">
                    <svg class="h-4 w-4 lg:h-5 lg:w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
                    </svg>
                    مطالبات
                </a>
            @endcan
        </div>
    </div>
@endif
                </div>
            </div>

            <!-- بخش سمت راست: پروفایل (تبلت به بالا) -->
            <div class="hidden md:flex items-center gap-2 lg:gap-3 xl:gap-4">
                <div class="relative" x-data="{ profileOpen: false }">
                    <button @click="profileOpen = !profileOpen" 
                        class="flex items-center gap-1.5 lg:gap-2.5 bg-white px-2 lg:px-3.5 py-1 lg:py-2 rounded-lg shadow-sm hover:shadow transition border border-gray-200 text-xs lg:text-sm">
                        <div class="h-6 w-6 lg:h-8 lg:w-8 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-xs lg:text-base">
                            {{ mb_substr(Auth::user()->name ?? 'کاربر', 0, 1) }}
                        </div>
                        <div class="text-right hidden lg:block">
                            <div class="font-medium text-gray-700">{{ Auth::user()->name ?? 'کاربر' }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ Str::limit(Auth::user()->email ?? '', 20) }}</div>
                        </div>
                        <svg class="h-3 w-3 lg:h-4 lg:w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    <!-- منوی کشویی پروفایل -->
                    <div x-show="profileOpen" @click.away="profileOpen = false" 
                        class="absolute left-0 mt-2 w-48 lg:w-56 bg-white rounded-lg shadow-xl py-1.5 z-50 border border-gray-200 text-xs lg:text-sm">
                        <a href="{{ route('profile.edit') }}" class="block px-3 lg:px-4 py-2 lg:py-2.5 text-gray-800 hover:bg-blue-50 transition flex items-center gap-2">
                            <svg class="h-4 w-4 lg:h-5 lg:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            پروفایل
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-right px-3 lg:px-4 py-2 lg:py-2.5 text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                                <svg class="h-4 w-4 lg:h-5 lg:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                                خروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- دکمه همبرگری (موبایل) -->
            <div class="flex md:hidden items-center">
                <button @click="open = !open" class="inline-flex items-center justify-center p-1.5 sm:p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6 sm:h-7 sm:w-7" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- منوی موبایل (کاملاً بهینه شده) -->
    <div :class="{'block': open, 'hidden': !open}" class="md:hidden border-t border-gray-200 bg-white/95 backdrop-blur-sm">
        <div class="pt-2 pb-4 space-y-1 px-3 sm:px-4">
            <!-- دسته‌بندی اصلی در موبایل -->
            <div class="text-xs font-semibold text-gray-500 mb-2 pr-2">منوی اصلی</div>
            
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="py-2.5">
                <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                داشبورد اصلی
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')" class="py-2.5">
                <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                داشبورد شخصی
            </x-responsive-nav-link>

            <div class="border-t border-gray-100 my-2"></div>
            <div class="text-xs font-semibold text-gray-500 mb-2 pr-2">مدیریت</div>

            @can('view cars')
                <x-responsive-nav-link :href="route('cars.index')" :active="request()->routeIs('cars.*')" class="py-2.5">
                    <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    خودروها
                </x-responsive-nav-link>
            @endcan

            @can('view investors')
                <x-responsive-nav-link :href="route('investors.index')" :active="request()->routeIs('investors.*')" class="py-2.5">
                    <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    سرمایه‌گذاران
                </x-responsive-nav-link>
            @endcan

            @can('view people')
                <x-responsive-nav-link :href="route('people.index')" :active="request()->routeIs('people.*')" class="py-2.5">
                    <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    اشخاص
                </x-responsive-nav-link>
            @endcan

            @can('view investments')
                <x-responsive-nav-link :href="route('investments.index')" :active="request()->routeIs('investments.*')" class="py-2.5">
                    <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    سرمایه‌گذاری‌ها
                </x-responsive-nav-link>
            @endcan

            @can('view sales')
                <x-responsive-nav-link :href="route('car-sales.index')" :active="request()->routeIs('car-sales.*')" class="py-2.5">
                    <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
                    </svg>
                    فروش‌ها
                </x-responsive-nav-link>
            @endcan

            <div class="border-t border-gray-100 my-2"></div>
            <div class="text-xs font-semibold text-gray-500 mb-2 pr-2">مدیریت مالی</div>

            @can('view assets')
                <x-responsive-nav-link :href="route('assets.index')" :active="request()->routeIs('assets.*')" class="py-2.5">
                    <svg class="h-5 w-5 inline ml-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    دارایی‌ها
                </x-responsive-nav-link>
            @endcan

            @can('view liabilities')
                <x-responsive-nav-link :href="route('liabilities.index')" :active="request()->routeIs('liabilities.*')" class="py-2.5">
                    <svg class="h-5 w-5 inline ml-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    تعهدات
                </x-responsive-nav-link>
            @endcan

            @can('view users')
                <div class="border-t border-gray-100 my-2"></div>
                <div class="text-xs font-semibold text-gray-500 mb-2 pr-2">مدیریت سیستم</div>
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')" class="py-2.5">
                    <svg class="h-5 w-5 inline ml-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    مدیریت کاربران
                </x-responsive-nav-link>
            @endcan
        </div>

        <!-- پروفایل در موبایل -->
        <div class="pt-3 pb-4 border-t border-gray-200 bg-gray-50/80 px-3 sm:px-4">
            <div class="flex items-center gap-3 px-2 py-2">
                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-base">
                    {{ mb_substr(Auth::user()->name ?? 'کاربر', 0, 1) }}
                </div>
                <div class="flex-1">
                    <div class="font-medium text-gray-800 text-sm">{{ Auth::user()->name ?? 'کاربر' }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">{{ Auth::user()->email ?? '' }}</div>
                </div>
            </div>
            <div class="mt-2 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="py-2.5">
                    <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    ویرایش پروفایل
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="py-2.5">
                        <svg class="h-5 w-5 inline ml-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        خروج از حساب
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<style>
    [x-cloak] { display: none !important; }
    .transition { transition: all 0.2s ease; }
    .backdrop-blur-sm { backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); }
    
    /* برای نمایش در صفحه‌های خیلی کوچک (زیر 400px) */
    @media (max-width: 400px) {
        .xs\:block { display: block; }
        .xs\:hidden { display: none; }
    }
</style>