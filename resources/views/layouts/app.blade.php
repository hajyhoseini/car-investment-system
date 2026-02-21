<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- عنوان صفحه – می‌تونی در viewها با @section('title') تغییر بدی -->
    <title>@yield('title', config('app.name', 'سیستم مدیریت سرمایه‌گذاری خودرو'))</title>

    <!-- توضیحات و کلمات کلیدی (اختیاری – برای SEO) -->
    <meta name="description" content="@yield('meta_description', 'سیستم مدیریت سرمایه‌گذاری در خودروها')">
    <meta name="keywords" content="@yield('meta_keywords', 'سرمایه گذاری, خودرو, مدیریت مالی')">

    <!-- فونت مناسب فارسی (وزیر) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite – css و js اصلی پروژه -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- استایل‌های اضافی یا override (اختیاری) -->
    <style>
        body {
            font-family: 'Vazirmatn', system-ui, -apple-system, sans-serif;
        }
    </style>

    <!-- المان‌های دینامیک (مثلاً favicon، manifest و ...) -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
</head>

<body class="font-vazir antialiased bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100">

    <div class="min-h-screen flex flex-col">
        <!-- نوار ناوبری -->
        @include('layouts.navigation')

        <!-- هدر صفحه (اختیاری – در viewها با @section('header') تعریف می‌شه) -->
        @hasSection('header')
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>
        @endif

        <!-- محتوای اصلی -->
        <main class="flex-grow">
            @yield('content')
        </main>

     
    </div>

    <!-- اسکریپت‌های اضافی اگر لازم باشه -->
    @stack('scripts')

</body>
</html>