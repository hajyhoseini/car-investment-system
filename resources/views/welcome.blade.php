<!DOCTYPE html>
<html lang="fa" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'سیستم مدیریت سرمایه‌گذاری خودرو') }}</title>

        <!-- فونت وزیر -->
        <style>
            @font-face {
                font-family: 'Vazir';
                src: url('/fonts/Vazir.woff2') format('woff2');
                font-weight: normal;
                font-style: normal;
                font-display: swap;
            }
            @font-face {
                font-family: 'Vazir';
                src: url('/fonts/Vazir.woff2') format('woff2');
                font-weight: bold;
                font-style: normal;
                font-display: swap;
            }
            @font-face {
                font-family: 'Vazir';
                src: url('/fonts/Vazir.woff2') format('woff2');
                font-weight: 300;
                font-style: normal;
                font-display: swap;
            }
        </style>

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */
                @import url('https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap');
                *{font-family: 'Vazirmatn', sans-serif;}
                /* بقیه استایل‌های tailwind مثل قبل */
            </style>
        @endif
    </head>
    <body class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-gray-800 text-gray-800 dark:text-gray-200 flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col font-sans">
        
        <header class="w-full lg:max-w-7xl max-w-[335px] text-sm mb-6">
            @if (Route::has('login'))
                <nav class="flex items-center justify-between gap-4">
                    <div class="flex items-center">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-2 rounded-lg shadow-md ml-3">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-gray-800 dark:text-white">سیستم مدیریت سرمایه‌گذاری خودرو</span>
                    </div>
                    <div class="flex items-center gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-block px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md transition transform hover:scale-105">
                                داشبورد
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-block px-6 py-2 text-blue-600 dark:text-blue-400 border-2 border-blue-600 dark:border-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg font-medium transition">
                                ورود
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-block px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-md transition transform hover:scale-105">
                                    ثبت‌نام
                                </a>
                            @endif
                        @endauth
                    </div>
                </nav>
            @endif
        </header>

        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow">
            <main class="flex max-w-[335px] w-full flex-col lg:max-w-6xl lg:flex-row items-center gap-8">
                <!-- بخش متنی خوش‌آمدگویی -->
                <div class="flex-1 p-8 lg:p-12 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700">
                    <h1 class="text-4xl lg:text-5xl font-bold mb-4 bg-gradient-to-l from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        مدیریت هوشمند سرمایه‌گذاری خودرو
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                        با این سیستم می‌توانید به راحتی خودروها، سرمایه‌گذاران، سرمایه‌گذاری‌ها و فروش را مدیریت کنید و سود هر سرمایه‌گذار را به صورت خودکار محاسبه نمایید.
                    </p>

                    <!-- ویژگی‌ها -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-gray-700 rounded-lg">
                            <div class="bg-blue-500 p-2 rounded-lg">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                            </div>
                            <span class="font-medium">مدیریت خودروها</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-green-50 dark:bg-gray-700 rounded-lg">
                            <div class="bg-green-500 p-2 rounded-lg">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium">مدیریت سرمایه‌گذاران</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-purple-50 dark:bg-gray-700 rounded-lg">
                            <div class="bg-purple-500 p-2 rounded-lg">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium">مدیریت سرمایه‌گذاری‌ها</span>
                        </div>
                        <div class="flex items-center gap-3 p-3 bg-yellow-50 dark:bg-gray-700 rounded-lg">
                            <div class="bg-yellow-500 p-2 rounded-lg">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="font-medium">محاسبه خودکار سود</span>
                        </div>
                    </div>

                    <!-- آمار کلی (اختیاری) -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ \App\Models\Car::count() }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">خودرو</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ \App\Models\Investor::count() }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">سرمایه‌گذار</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ \App\Models\Investment::count() }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">سرمایه‌گذاری</div>
                        </div>
                        <div class="text-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ \App\Models\CarSale::count() }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">فروش</div>
                        </div>
                    </div>

                    @auth
                        <div class="mt-8 text-center">
                            <a href="{{ route('dashboard') }}" class="inline-block px-8 py-3 bg-gradient-to-l from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition">
                                ورود به داشبورد
                                <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7-7-7m14-6l-7 7-7-7"></path>
                                </svg>
                            </a>
                        </div>
                    @else
                        <div class="mt-8 flex gap-4 justify-center">
                            <a href="{{ route('login') }}" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition">
                                ورود به سیستم
                            </a>
                            <a href="{{ route('register') }}" class="px-8 py-3 border-2 border-blue-600 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-gray-700 font-bold rounded-xl transition">
                                ثبت‌نام
                            </a>
                        </div>
                    @endauth
                </div>

                <!-- تصویر یا آیکون -->
                <div class="lg:w-96 w-full">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl p-8 shadow-2xl">
                        <svg class="w-full text-white" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M200 50L300 100V200L200 250L100 200V100L200 50Z" stroke="currentColor" stroke-width="2" fill="white" fill-opacity="0.1"/>
                            <circle cx="200" cy="150" r="30" stroke="currentColor" stroke-width="2" fill="white" fill-opacity="0.2"/>
                            <path d="M150 120L120 150L150 180" stroke="currentColor" stroke-width="3" fill="none"/>
                            <path d="M250 120L280 150L250 180" stroke="currentColor" stroke-width="3" fill="none"/>
                            <circle cx="170" cy="140" r="5" fill="white"/>
                            <circle cx="230" cy="140" r="5" fill="white"/>
                            <path d="M180 180Q200 200 220 180" stroke="currentColor" stroke-width="3" fill="none"/>
                            <path d="M130 80L160 70M270 70L300 80" stroke="currentColor" stroke-width="3"/>
                            <path d="M100 220L140 210M260 210L300 220" stroke="currentColor" stroke-width="3"/>
                        </svg>
                        <div class="text-center text-white mt-4">
                            <span class="text-sm opacity-90">مدیریت هوشمند سرمایه‌گذاری خودرو</span>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <footer class="w-full text-center mt-8 text-sm text-gray-500 dark:text-gray-400">
            <p>© ۲۰۲۵ - تمامی حقوق محفوظ است. سیستم مدیریت سرمایه‌گذاری خودرو</p>
        </footer>
    </body>
</html>