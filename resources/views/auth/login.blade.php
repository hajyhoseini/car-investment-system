<x-guest-layout>
    <!-- لوگو و عنوان -->
    <div class="text-center mb-8" dir="rtl">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4 rounded-2xl shadow-2xl inline-block mb-4">
            <svg class="h-16 w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">ورود به سیستم</h2>
        <p class="text-gray-600 dark:text-gray-300 mt-2">به پنل مدیریت سرمایه‌گذاری خودرو خوش آمدید</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('ایمیل')" class="text-gray-700 dark:text-gray-300 mb-2" />
            <div class="relative">
                <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                    </svg>
                </span>
                <x-text-input id="email" 
                    class="block mt-1 w-full pr-10 py-3 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    autofocus 
                    autocomplete="username" 
                    placeholder="your@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('رمز عبور')" class="text-gray-700 dark:text-gray-300 mb-2" />
            <div class="relative">
                <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </span>
                <x-text-input id="password" 
                    class="block mt-1 w-full pr-10 py-3 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    type="password"
                    name="password"
                    required 
                    autocomplete="current-password" 
                    placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="mr-2 text-sm text-gray-600 dark:text-gray-400">{{ __('مرا به خاطر بسپار') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 dark:text-blue-400 hover:underline" href="{{ route('password.request') }}">
                    {{ __('رمز را فراموش کرده‌اید؟') }}
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <div>
            <button type="submit" class="w-full py-3 px-4 bg-gradient-to-l from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition duration-200">
                <span class="flex items-center justify-center">
                    <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    {{ __('ورود به سیستم') }}
                </span>
            </button>
        </div>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="text-center mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ __('حساب کاربری ندارید؟') }}
                    <a href="{{ route('register') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                        {{ __('ثبت‌نام کنید') }}
                    </a>
                </p>
            </div>
        @endif
    </form>
</x-guest-layout>