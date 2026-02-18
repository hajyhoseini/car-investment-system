<x-guest-layout>
    <!-- لوگو و عنوان -->
    <div class="text-center mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4 rounded-2xl shadow-2xl inline-block mb-4">
            <svg class="h-16 w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
            </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">بازیابی رمز عبور</h2>
        <p class="text-gray-600 dark:text-gray-300 mt-2">نگران نباشید! ما کمکتان می‌کنیم</p>
    </div>

    <!-- توضیحات -->
    <div class="mb-6 p-4 bg-blue-50 dark:bg-gray-700 rounded-xl text-sm text-gray-600 dark:text-gray-300 border border-blue-100 dark:border-gray-600">
        <div class="flex items-start">
            <svg class="h-5 w-5 text-blue-500 dark:text-blue-400 ml-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>
                {{ __('رمز عبور خود را فراموش کرده‌اید؟ مشکلی نیست! فقط آدرس ایمیل خود را وارد کنید تا لینک بازیابی رمز عبور را برایتان ارسال کنیم.') }}
            </span>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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
                <x-text-input 
                    id="email" 
                    class="block mt-1 w-full pr-10 py-3 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    autofocus 
                    placeholder="your@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500" />
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full py-3 px-4 bg-gradient-to-l from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition duration-200">
                <span class="flex items-center justify-center">
                    <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    {{ __('ارسال لینک بازیابی') }}
                </span>
            </button>
        </div>

        <!-- Back to Login Link -->
        <div class="text-center mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('رمز عبور خود را به خاطر آوردید؟') }}
                <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                    {{ __('بازگشت به صفحه ورود') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>