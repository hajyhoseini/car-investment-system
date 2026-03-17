@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">ویرایش دارایی: {{ $asset->name }}</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('assets.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            بازگشت به لیست
                        </a>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('assets.update', $asset) }}" id="assetForm">
                    @csrf
                    @method('PUT')

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
                            :selected="old('type', $asset->type)"
                            placeholder="انتخاب کنید..."
                            required="true"
                        />

                        <!-- نام دارایی -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نام <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $asset->name) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('name') border-red-500 @enderror" 
                                   placeholder="مثال: حساب سپهر" required>
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- فیلدهای مخصوص حساب بانکی -->
                        <div id="bankFields" class="{{ $asset->type == 'bank' ? 'md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6' : 'hidden md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6' }}">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">نام بانک</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name', $asset->bank_name) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('bank_name') border-red-500 @enderror" 
                                       placeholder="مثال: ملت">
                                @error('bank_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">شماره حساب</label>
                                <input type="text" name="account_number" value="{{ old('account_number', $asset->account_number) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('account_number') border-red-500 @enderror" 
                                       placeholder="مثال: 1234567890">
                                @error('account_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">شماره کارت</label>
                                <input type="text" name="card_number" value="{{ old('card_number', $asset->card_number) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('card_number') border-red-500 @enderror" 
                                       placeholder="مثال: 1234-5678-9012-3456" 
                                       maxlength="20">
                                @error('card_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">شماره شبا</label>
                                <input type="text" name="sheba_number" value="{{ old('sheba_number', $asset->sheba_number) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('sheba_number') border-red-500 @enderror" 
                                       placeholder="مثال: IR12345678901234567890" 
                                       maxlength="30">
                                @error('sheba_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- مقدار -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" id="amountLabel">
                                @if($asset->type == 'bank')
                                    موجودی (ریال)
                                @elseif($asset->type == 'dollar')
                                    مقدار (دلار)
                                @elseif($asset->type == 'gold')
                                    مقدار (گرم)
                                @else
                                    مقدار
                                @endif
                            </label>
                            <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $asset->amount) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('amount') border-red-500 @enderror" 
                                   placeholder="۰" required>
                            @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- ارزش به ریال با کامپوننت price-input -->
                        <div id="valueField" @if($asset->type == 'bank') style="display: none;" @endif>
                            <x-price-input 
                                name="value"
                                label="ارزش به ریال"
                                :value="old('value', $asset->value)"
                                placeholder="مثال: ۵۰,۰۰۰,۰۰۰"
                                :min="0"
                                :required="$asset->type != 'bank'"
                                formId="assetForm"
                            />
                            <p class="text-xs text-gray-500 mt-1">برای دلار و طلا، ارزش به ریال را وارد کنید</p>
                        </div>

                        <!-- وضعیت فعال/غیرفعال -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت</label>
                            <div class="mt-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $asset->is_active) ? 'checked' : '' }} 
                                           class="rounded border-gray-300 text-amber-600 shadow-sm focus:border-amber-300 focus:ring focus:ring-amber-200 focus:ring-opacity-50">
                                    <span class="mr-2 text-sm text-gray-600">فعال</span>
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">در صورت غیرفعال بودن، دارایی در لیست‌ها نمایش داده نمی‌شود</p>
                            @error('is_active') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                            <textarea name="description" rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('description') border-red-500 @enderror"
                                      placeholder="توضیحات اضافی">{{ old('description', $asset->description) }}</textarea>
                            @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- اطلاعات اضافی (فقط نمایش) -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-xl">
                        <h3 class="text-lg font-semibold mb-4 text-amber-600">اطلاعات ثبت</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm text-gray-600">تاریخ ایجاد:</span>
                                <span class="block font-medium">{{ \Morilog\Jalali\Jalalian::fromCarbon($asset->created_at)->format('Y/m/d H:i') }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">آخرین به‌روزرسانی:</span>
                                <span class="block font-medium">{{ \Morilog\Jalali\Jalalian::fromCarbon($asset->updated_at)->format('Y/m/d H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('assets.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105">
                            به‌روزرسانی دارایی
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // منتظر می‌مونیم تا DOM کامل لود بشه و کامپوننت‌ها آماده بشن
    document.addEventListener('DOMContentLoaded', function() {
        // یک تابع برای آپدیت کردن فیلدها براساس نوع دارایی
        function updateFieldsByType() {
            // مقدار نوع دارایی رو از کامپوننت می‌گیریم
            const typeSelect = document.querySelector('[x-data*="type"]');
            if (!typeSelect || !typeSelect.__x) return;
            
            const typeData = typeSelect.__x.$data;
            const type = typeData.selectedValue;
            
            const amountLabel = document.getElementById('amountLabel');
            const amountInput = document.getElementById('amount');
            const valueField = document.getElementById('valueField');
            const bankFields = document.getElementById('bankFields');
            const valueInput = valueField ? valueField.querySelector('[name="value"]') : null;
            
            // مخفی کردن همه فیلدهای شرطی اول
            bankFields.classList.add('hidden');
            
            switch(type) {
                case 'bank':
                    amountLabel.textContent = 'موجودی (ریال)';
                    amountInput.step = '1';
                    amountInput.placeholder = 'مثال: ۵۰,۰۰۰,۰۰۰';
                    valueField.style.display = 'none';
                    bankFields.classList.remove('hidden');
                    if (valueInput && valueInput._x_dataStack) {
                        valueInput._x_dataStack[0].setRequired?.(false);
                    }
                    break;
                case 'dollar':
                    amountLabel.textContent = 'مقدار (دلار)';
                    amountInput.step = '0.01';
                    amountInput.placeholder = 'مثال: ۱۰۰۰';
                    valueField.style.display = 'block';
                    bankFields.classList.add('hidden');
                    if (valueInput && valueInput._x_dataStack) {
                        valueInput._x_dataStack[0].setRequired?.(true);
                    }
                    break;
                case 'gold':
                    amountLabel.textContent = 'مقدار (گرم)';
                    amountInput.step = '0.001';
                    amountInput.placeholder = 'مثال: ۱۸.۵';
                    valueField.style.display = 'block';
                    bankFields.classList.add('hidden');
                    if (valueInput && valueInput._x_dataStack) {
                        valueInput._x_dataStack[0].setRequired?.(true);
                    }
                    break;
                default:
                    amountLabel.textContent = 'مقدار';
                    amountInput.step = '0.01';
                    amountInput.placeholder = '۰';
                    valueField.style.display = 'block';
                    bankFields.classList.add('hidden');
            }
        }

        // گوش دادن به تغییرات نوع دارایی
        const typeSelect = document.querySelector('[x-data*="type"]');
        if (typeSelect) {
            // با هر تغییری در کامپوننت
            typeSelect.addEventListener('change', function(e) {
                setTimeout(updateFieldsByType, 50);
            });
            
            // برای مقداردهی اولیه
            setTimeout(updateFieldsByType, 200);
        }

        // اطمینان از اینکه مقدار value موقع ارسال فرم درست هست
        document.getElementById('assetForm')?.addEventListener('submit', function(e) {
            const typeSelect = document.querySelector('[x-data*="type"]');
            if (!typeSelect || !typeSelect.__x) return true;
            
            const typeData = typeSelect.__x.$data;
            const type = typeData.selectedValue;
            
            // برای حساب بانکی، value نباید ارسال بشه
            if (type === 'bank') {
                const valueInput = document.querySelector('[name="value"]');
                if (valueInput) {
                    valueInput.disabled = true; // غیرفعال کردن ارسال فیلد
                }
                
                // فعال کردن فیلدهای بانکی برای ارسال
                const bankInputs = document.querySelectorAll('#bankFields input');
                bankInputs.forEach(input => {
                    input.disabled = false;
                });
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