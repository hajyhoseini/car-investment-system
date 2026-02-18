<x-guest-layout>
    <!-- لوگو و عنوان -->
    <div class="text-center mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-4 rounded-2xl shadow-2xl inline-block mb-4">
            <svg class="h-16 w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
            </svg>
        </div>
        <h2 class="text-3xl font-bold text-gray-800 dark:text-white">ثبت‌نام در سیستم</h2>
        <p class="text-gray-600 dark:text-gray-300 mt-2">به جمع سرمایه‌گذاران ما بپیوندید</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('نام و نام خانوادگی')" class="text-gray-700 dark:text-gray-300 mb-2" />
            <div class="relative">
                <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </span>
                <x-text-input id="name" 
                    class="block mt-1 w-full pr-10 py-3 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                    type="text" 
                    name="name" 
                    :value="old('name')" 
                    required 
                    autofocus 
                    autocomplete="name" 
                    placeholder="علی محمدی" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500" />
        </div>

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
                    autocomplete="new-password" 
                    placeholder="حداقل ۸ کاراکتر" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('تکرار رمز عبور')" class="text-gray-700 dark:text-gray-300 mb-2" />
            <div class="relative">
                <span class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </span>
                <x-text-input id="password_confirmation" 
                    class="block mt-1 w-full pr-10 py-3 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                    type="password"
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password" 
                    placeholder="تکرار رمز عبور" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500" />
        </div>

        <!-- Register Button -->
        <div>
            <button type="submit" class="w-full py-3 px-4 bg-gradient-to-l from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition duration-200">
                <span class="flex items-center justify-center">
                    <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    {{ __('ثبت‌نام در سیستم') }}
                </span>
            </button>
        </div>

        <!-- Login Link -->
        <div class="text-center mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ __('قبلاً ثبت‌نام کرده‌اید؟') }}
                <a href="{{ route('login') }}" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                    {{ __('وارد شوید') }}
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>