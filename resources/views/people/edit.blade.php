@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">ویرایش شخص: {{ $person->full_name }}</h2>
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

                <form method="POST" action="{{ route('people.update', $person) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- نام کامل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نام کامل <span class="text-red-500">*</span></label>
                            <input type="text" name="full_name" value="{{ old('full_name', $person->full_name) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
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
                            :selected="old('type', $person->type)"
                            placeholder="انتخاب کنید..."
                            required="true"
                        />

                        <!-- کد ملی -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">کد ملی</label>
                            <input type="text" name="national_code" value="{{ old('national_code', $person->national_code) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                   placeholder="کد ملی ۱۰ رقمی">
                        </div>

                        <!-- تلفن -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تلفن</label>
                            <input type="text" name="phone" value="{{ old('phone', $person->phone) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                   placeholder="مثال: ۰۹۱۲۳۴۵۶۷۸۹">
                        </div>

                        <!-- ایمیل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                            <input type="email" name="email" value="{{ old('email', $person->email) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                   placeholder="info@example.com">
                        </div>

                        <!-- کد پستی -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">کد پستی</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code', $person->postal_code) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                   placeholder="کد پستی ۱۰ رقمی">
                        </div>

                        <!-- کد اقتصادی -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">کد اقتصادی</label>
                            <input type="text" name="economic_code" value="{{ old('economic_code', $person->economic_code) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                   placeholder="کد اقتصادی">
                        </div>

                        <!-- تاریخ تولد (شمسی) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ تولد</label>
                            <input type="text" name="birth_date" id="birth_date" 
                                   value="{{ old('birth_date', $person->birth_date ? \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($person->birth_date))->format('Y/m/d') : '') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('birth_date') border-red-500 @enderror"
                                   placeholder="مثال: ۱۳۷۰/۰۱/۰۱" autocomplete="off">
                            @error('birth_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- حقوقی / حقیقی -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_legal" id="is_legal" value="1" {{ old('is_legal', $person->is_legal) ? 'checked' : '' }}
                                   class="ml-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="is_legal" class="text-sm font-medium text-gray-700">شخص حقوقی است</label>
                        </div>

                        <!-- نام شرکت (نمایش شرطی) -->
                        <div id="company_field" class="{{ old('is_legal', $person->is_legal) ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-2">نام شرکت</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $person->company_name) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                   placeholder="مثال: شرکت بازرگانی البرز">
                        </div>

                        <!-- تصویر فعلی -->
                        @if($person->avatar)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تصویر فعلی</label>
                            <img src="{{ $person->avatar_url }}" alt="{{ $person->full_name }}" class="w-20 h-20 rounded-full object-cover">
                        </div>
                        @endif

                        <!-- تصویر جدید -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تصویر جدید</label>
                            <input type="file" name="avatar" accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <p class="text-xs text-gray-500 mt-1">فرمت‌های مجاز: jpeg, png, jpg (حداکثر ۲ مگابایت)</p>
                        </div>

                        <!-- آدرس -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">آدرس</label>
                            <textarea name="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                      placeholder="آدرس کامل">{{ old('address', $person->address) }}</textarea>
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                      placeholder="توضیحات اضافی">{{ old('description', $person->description) }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2">
                        <a href="{{ route('people.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-green-500 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg transition">
                            به‌روزرسانی شخص
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
@endpush

@push('scripts')
<script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        // تقویم شمسی برای تاریخ تولد
        $('#birth_date').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: true,
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