@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6">افزودن دارایی جدید</h2>

                <form method="POST" action="{{ route('assets.store') }}" id="assetForm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- نوع دارایی با کامپوننت -->
                        <x-searchable-select 
                            name="type"
                            label="نوع دارایی"
                            :options="[
                                ['id' => 'bank', 'text' => 'حساب بانکی'],
                                ['id' => 'dollar', 'text' => 'دلار'],
                                ['id' => 'gold', 'text' => 'طلا']
                            ]"
                            :selected="old('type')"
                            placeholder="انتخاب کنید..."
                            required="true"
                        />

                        <!-- نام دارایی -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">نام <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                   placeholder="مثال: حساب سپهر" required>
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- فیلدهای مخصوص حساب بانکی -->
                        <div id="bankFields" class="hidden md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">نام بانک</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                       placeholder="مثال: ملت">
                                @error('bank_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">شماره حساب</label>
                                <input type="text" name="account_number" value="{{ old('account_number') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                       placeholder="مثال: 1234567890">
                                @error('account_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">شماره کارت</label>
                                <input type="text" name="card_number" value="{{ old('card_number') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                       placeholder="مثال: 1234-5678-9012-3456" 
                                       maxlength="20">
                                @error('card_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">شماره شبا</label>
                                <input type="text" name="sheba_number" value="{{ old('sheba_number') }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                       placeholder="مثال: IR12345678901234567890" 
                                       maxlength="30">
                                @error('sheba_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- مقدار (بسته به نوع تغییر می‌کند) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700" id="amountLabel">مقدار</label>
                            <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                   placeholder="۰" required>
                            @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- ارزش به ریال (برای دلار و طلا) با کامپوننت price-input -->
                        <div id="valueField" class="{{ old('type') == 'bank' ? 'hidden' : '' }}">
                            <x-price-input 
                                name="value"
                                label="ارزش به ریال"
                                :value="old('value')"
                                placeholder="مثال: ۵۰,۰۰۰,۰۰۰"
                                :min="0"
                                formId="assetForm"
                            />
                        </div>

                        <!-- وضعیت فعال/غیرفعال -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">وضعیت</label>
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="mr-2 text-sm text-gray-600">فعال</span>
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">در صورت غیرفعال بودن، دارایی در لیست‌ها نمایش داده نمی‌شود</p>
                            @error('is_active') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">توضیحات</label>
                            <textarea name="description" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                      placeholder="توضیحات اضافی">{{ old('description') }}</textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('assets.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
                            انصراف
                        </a>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
                            ثبت دارایی
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // تابع برای آپدیت کردن فیلدها براساس نوع دارایی
        function updateFieldsByType() {
            const typeSelect = document.querySelector('[x-data*="type"]');
            if (!typeSelect || !typeSelect.__x) return;
            
            const typeData = typeSelect.__x.$data;
            const type = typeData.selectedValue;
            
            const amountLabel = document.getElementById('amountLabel');
            const amountInput = document.getElementById('amount');
            const valueField = document.getElementById('valueField');
            const bankFields = document.getElementById('bankFields');
            
            // مخفی کردن همه فیلدهای شرطی اول
            bankFields.classList.add('hidden');
            valueField.classList.remove('hidden');
            
            switch(type) {
                case 'bank':
                    amountLabel.textContent = 'موجودی (ریال)';
                    amountInput.step = '1';
                    amountInput.placeholder = 'مثال: ۵۰,۰۰۰,۰۰۰';
                    valueField.classList.add('hidden');
                    bankFields.classList.remove('hidden');
                    break;
                case 'dollar':
                    amountLabel.textContent = 'مقدار (دلار)';
                    amountInput.step = '0.01';
                    amountInput.placeholder = 'مثال: ۱۰۰۰';
                    break;
                case 'gold':
                    amountLabel.textContent = 'مقدار (گرم)';
                    amountInput.step = '0.001';
                    amountInput.placeholder = 'مثال: ۱۸.۵';
                    break;
                default:
                    amountLabel.textContent = 'مقدار';
                    amountInput.step = '0.01';
                    amountInput.placeholder = '۰';
            }
        }

        // گوش دادن به تغییرات نوع دارایی
        const typeSelect = document.querySelector('[x-data*="type"]');
        if (typeSelect) {
            typeSelect.addEventListener('change', function(e) {
                setTimeout(updateFieldsByType, 50);
            });
            
            // مقداردهی اولیه
            setTimeout(updateFieldsByType, 200);
        }

        // مدیریت ارسال فرم
        document.getElementById('assetForm')?.addEventListener('submit', function(e) {
            const typeSelect = document.querySelector('[x-data*="type"]');
            if (!typeSelect || !typeSelect.__x) return true;
            
            const typeData = typeSelect.__x.$data;
            const type = typeData.selectedValue;
            
            // برای حساب بانکی، فیلدهای اضافی رو فعال می‌ذاریم
            if (type === 'bank') {
                // مقدار value رو خالی می‌کنیم چون نیازی نیست
                const valueInput = document.querySelector('[name="value"]');
                if (valueInput) {
                    valueInput.disabled = true;
                }
            } else {
                // برای غیرحساب بانکی، فیلدهای بانکی رو غیرفعال می‌کنیم
                const bankInputs = document.querySelectorAll('#bankFields input');
                bankInputs.forEach(input => {
                    input.disabled = true;
                });
                
                const valueInput = document.querySelector('[name="value"]');
                if (valueInput) {
                    valueInput.disabled = false;
                }
            }
            
            return true;
        });
    });
</script>
@endpush
@endsection