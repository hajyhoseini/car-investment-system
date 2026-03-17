@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">
                        {{ $type === 'income' ? 'ثبت دریافت جدید' : 'ثبت پرداخت جدید' }}
                    </h2>
                    <a href="{{ route('transactions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
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

                <form method="POST" action="{{ route('transactions.store') }}" id="transactionForm">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- مبلغ با کامپوننت price-input -->
                        <x-price-input 
                            name="amount"
                            label="مبلغ (ریال)"
                            :value="old('amount')"
                            placeholder="مثال: ۱,۰۰۰,۰۰۰"
                            :min="1000"
                            :required="true"
                            formId="transactionForm"
                        />

                        <!-- تاریخ تراکنش (شمسی) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ تراکنش <span class="text-red-500">*</span></label>
                            <input type="text" name="transaction_date" id="transaction_date" 
                                   value="{{ old('transaction_date', now_jalali('Y/m/d')) }}" 
                                   class="jalali-datepicker w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('transaction_date') border-red-500 @enderror"
                                   placeholder="مثال: ۱۴۰۲/۱۲/۲۵" autocomplete="off" required>
                            @error('transaction_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- حساب (شرطی بر اساس نوع) با کامپوننت searchable-select -->
                        @if($type === 'income')
                            <div>
                                <x-searchable-select 
                                    name="to_account_id"
                                    label="حساب مقصد (دریافت به)"
                                    :options="$accountOptions"
                                    :selected="old('to_account_id')"
                                    placeholder="انتخاب کنید..."
                                    required="true"
                                />
                                <p class="text-xs text-gray-500 mt-1" id="balance_preview">
                                    موجودی پس از دریافت: <span id="balance_after">0</span> ریال
                                </p>
                            </div>
                        @else
                            <div>
                                <x-searchable-select 
                                    name="from_account_id"
                                    label="حساب مبدأ (پرداخت از)"
                                    :options="$accountOptions"
                                    :selected="old('from_account_id')"
                                    placeholder="انتخاب کنید..."
                                    required="true"
                                />
                                <p class="text-xs text-gray-500 mt-1" id="balance_preview">
                                    موجودی پس از پرداخت: <span id="balance_after">0</span> ریال
                                </p>
                            </div>
                        @endif

                        <!-- شخص مرتبط با کامپوننت searchable-select -->
                        <div>
                            <x-searchable-select 
                                name="person_id"
                                label="شخص مرتبط"
                                :options="$personOptions"
                                :selected="old('person_id')"
                                placeholder="انتخاب کنید..."
                            />
                        </div>

                        <!-- روش پرداخت با کامپوننت searchable-select -->
                        <div>
                            <x-searchable-select 
                                name="payment_method_id"
                                label="روش پرداخت"
                                :options="$paymentMethodOptions"
                                :selected="old('payment_method_id')"
                                placeholder="انتخاب کنید..."
                            />
                        </div>

                        <!-- شماره چک (نمایش شرطی) -->
                        <div id="check_fields" class="hidden col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">شماره چک</label>
                                <input type="text" name="check_number" value="{{ old('check_number') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ چک (شمسی)</label>
                                <input type="text" name="check_date" id="check_date" 
                                       value="{{ old('check_date') }}" 
                                       class="jalali-datepicker w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                       placeholder="مثال: ۱۴۰۲/۱۲/۲۵" autocomplete="off">
                            </div>
                        </div>

                        <!-- دارایی مرتبط با کامپوننت searchable-select -->
                        <div>
                            <x-searchable-select 
                                name="asset_id"
                                label="دارایی مرتبط"
                                :options="$assetOptions"
                                :selected="old('asset_id')"
                                placeholder="انتخاب کنید..."
                            />
                        </div>

                        <!-- وضعیت با کامپوننت searchable-select -->
                        <div>
                            <x-searchable-select 
                                name="status"
                                label="وضعیت"
                                :options="[
                                    ['id' => 'pending', 'text' => 'در انتظار'],
                                    ['id' => 'completed', 'text' => 'تکمیل شده'],
                                    ['id' => 'cancelled', 'text' => 'لغو شده']
                                ]"
                                :selected="old('status', 'pending')"
                                placeholder="انتخاب کنید..."
                                required="true"
                            />
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                            <input type="text" name="description" value="{{ old('description') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                   placeholder="توضیح کوتاه">
                        </div>

                        <!-- یادداشت‌ها -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">یادداشت‌ها</label>
                            <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                      placeholder="یادداشت‌های اضافی...">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2">
                        <a href="{{ route('transactions.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition">
                            {{ $type === 'income' ? 'ثبت دریافت' : 'ثبت پرداخت' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">

<script>
    $(document).ready(function() {
        // تقویم شمسی
        $('.jalali-datepicker').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: true,
            calendar: {
                persian: true
            }
        });
    });

    // نمایش فیلدهای چک در صورت انتخاب روش پرداخت "چک"
    document.addEventListener('DOMContentLoaded', function() {
        const paymentSelect = document.querySelector('[x-data*="payment_method_id"]');
        const checkFields = document.getElementById('check_fields');
        
        function updateCheckFields() {
            if (!paymentSelect || !paymentSelect.__x) return;
            
            const paymentData = paymentSelect.__x.$data;
            const selectedId = paymentData.selectedValue;
            
            // اینجا باید بررسی کنی که آیا روش پرداخت انتخاب شده چک هست یا نه
            // برای این کار می‌تونی از data-attribute استفاده کنی
            if (selectedId) {
                const selectedOption = paymentData.options.find(opt => opt.id == selectedId);
                if (selectedOption && selectedOption.text.includes('چک')) {
                    checkFields.classList.remove('hidden');
                } else {
                    checkFields.classList.add('hidden');
                }
            } else {
                checkFields.classList.add('hidden');
            }
        }

        if (paymentSelect) {
            paymentSelect.addEventListener('change', function() {
                setTimeout(updateCheckFields, 100);
            });
            setTimeout(updateCheckFields, 200);
        }
    });

    // محاسبه موجودی پس از تراکنش
    document.addEventListener('DOMContentLoaded', function() {
        const amountInput = document.querySelector('[name="amount"]');
        const accountSelect = document.querySelector('[x-data*="account_id"]');
        const balanceAfterSpan = document.getElementById('balance_after');
        const transactionType = '{{ $type }}';
        
        function updateBalanceAfter() {
            if (!accountSelect || !accountSelect.__x || !balanceAfterSpan) return;
            
            const accountData = accountSelect.__x.$data;
            const selectedId = accountData.selectedValue;
            
            if (!selectedId) {
                balanceAfterSpan.textContent = '0';
                return;
            }
            
            const selectedOption = accountData.options.find(opt => opt.id == selectedId);
            const currentBalance = parseFloat(selectedOption?.data?.balance || 0);
            
            // گرفتن مقدار عددی از کامپوننت price-input
            let amount = 0;
            if (amountInput && amountInput._x_dataStack) {
                amount = amountInput._x_dataStack[0].getNumericValue?.() || 0;
            }
            
            let balanceAfter = currentBalance;
            if (transactionType === 'income') {
                balanceAfter = currentBalance + amount;
            } else {
                balanceAfter = currentBalance - amount;
            }
            
            balanceAfterSpan.textContent = new Intl.NumberFormat('fa-IR').format(balanceAfter);
        }
        
        if (amountInput) {
            amountInput.addEventListener('input', function() {
                setTimeout(updateBalanceAfter, 100);
            });
        }
        
        if (accountSelect) {
            accountSelect.addEventListener('change', function() {
                setTimeout(updateBalanceAfter, 100);
            });
        }
        
        setTimeout(updateBalanceAfter, 200);
    });
</script>
@endpush