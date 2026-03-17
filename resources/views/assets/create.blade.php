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
            const valueField = document.getElementById('valueField');
            
            switch(type) {
                case 'bank':
                    amountLabel.textContent = 'موجودی (ریال)';
                    valueField.classList.add('hidden');
                    break;
                case 'dollar':
                    amountLabel.textContent = 'مقدار (دلار)';
                    valueField.classList.remove('hidden');
                    break;
                case 'gold':
                    amountLabel.textContent = 'مقدار (گرم)';
                    valueField.classList.remove('hidden');
                    break;
                default:
                    amountLabel.textContent = 'مقدار';
                    valueField.classList.remove('hidden');
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
                    valueInput.value = ''; // مقدار رو خالی می‌کنیم
                }
            }
            
            return true;
        });
    });
</script>
@endpush
@endsection