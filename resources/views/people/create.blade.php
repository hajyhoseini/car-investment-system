@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">افزودن شخص جدید</h2>
                    <a href="{{ route('people.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        بازگشت
                    </a>
                </div>

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('people.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- نام کامل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نام کامل <span class="text-red-500">*</span></label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                   placeholder="مثال: علی محمدی" required>
                        </div>

                        <!-- نوع شخص با کامپوننت -->
                        <x-searchable-select 
                            name="type"
                            label="نوع شخص"
                            :options="[
                                ['id' => 'buyer', 'text' => 'خریدار'],
                                ['id' => 'seller', 'text' => 'فروشنده'],
                                ['id' => 'creditor', 'text' => 'طلبکار'],
                                ['id' => 'debtor', 'text' => 'بدهکار'],
                                ['id' => 'other', 'text' => 'سایر']
                            ]"
                            :selected="old('type')"
                            placeholder="انتخاب کنید..."
                            required="true"
                        />

                        <!-- کد ملی -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">کد ملی</label>
                            <input type="text" name="national_code" value="{{ old('national_code') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                   placeholder="کد ملی ۱۰ رقمی">
                        </div>

                        <!-- تلفن -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تلفن</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                   placeholder="مثال: ۰۹۱۲۳۴۵۶۷۸۹">
                        </div>

                        <!-- ایمیل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                            <input type="email" name="email" value="{{ old('email') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                   placeholder="info@example.com">
                        </div>

                        <!-- کد پستی -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">کد پستی</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                   placeholder="کد پستی ۱۰ رقمی">
                        </div>

                        <!-- کد اقتصادی -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">کد اقتصادی</label>
                            <input type="text" name="economic_code" value="{{ old('economic_code') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                   placeholder="کد اقتصادی">
                        </div>

                        <!-- تاریخ تولد (شمسی) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ تولد</label>
                            <input type="text" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('birth_date') border-red-500 @enderror"
                                   placeholder="مثال: ۱۳۷۰/۰۱/۰۱" autocomplete="off">
                            @error('birth_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- حقوقی / حقیقی -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_legal" id="is_legal" value="1" {{ old('is_legal') ? 'checked' : '' }}
                                   class="ml-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="is_legal" class="text-sm font-medium text-gray-700">شخص حقوقی است</label>
                        </div>

                        <!-- نام شرکت (نمایش شرطی) -->
                        <div id="company_field" class="{{ old('is_legal') ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-2">نام شرکت</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                   placeholder="مثال: شرکت بازرگانی البرز">
                        </div>

                        <!-- تصویر -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تصویر پروفایل</label>
                            <input type="file" name="avatar" accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <p class="text-xs text-gray-500 mt-1">فرمت‌های مجاز: jpeg, png, jpg (حداکثر ۲ مگابایت)</p>
                        </div>

                        <!-- آدرس -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">آدرس</label>
                            <textarea name="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                      placeholder="آدرس کامل">{{ old('address') }}</textarea>
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                      placeholder="توضیحات اضافی">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2">
                        <a href="{{ route('people.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition">
                            ذخیره شخص
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')

<script>
    $(document).ready(function() {
        // تقویم شمسی برای تاریخ تولد
        $('#birth_date').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: false,
            calendar: {
                persian: true
            },
            toolbox: {
                calendarSwitch: {
                    enabled: false
                }
            }
        });
    });

    // نمایش/مخفی کردن فیلد شرکت
    document.getElementById('is_legal').addEventListener('change', function() {
        const companyField = document.getElementById('company_field');
        if (this.checked) {
            companyField.classList.remove('hidden');
        } else {
            companyField.classList.add('hidden');
        }
    });
</script>
@endpush