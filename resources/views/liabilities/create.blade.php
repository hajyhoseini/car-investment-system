@extends('layouts.app')

@section('styles')
<style>
    /* استایل اضافی در صورت نیاز */
</style>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">ثبت تعهد جدید</h2>
                    <a href="{{ route('liabilities.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        بازگشت به لیست
                    </a>
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

                <form method="POST" action="{{ route('liabilities.store') }}" id="liabilityForm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- نوع تعهد با کامپوننت searchable-select -->
                        <div class="md:col-span-2">
                            <x-searchable-select 
                                name="type"
                                label="نوع تعهد"
                                :options="[
                                    ['id' => 'debt', 'text' => 'بدهی'],
                                    ['id' => 'check', 'text' => 'چک'],
                                    ['id' => 'installment', 'text' => 'قسط']
                                ]"
                                :selected="old('type')"
                                placeholder="انتخاب کنید..."
                                required="true"
                            />
                            @error('type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- شخص مرتبط با قابلیت جستجو -->
                        <div class="md:col-span-2">
                            <x-searchable-select 
                                name="person_id"
                                label="شخص مرتبط (طلبکار/بدهکار)"
                                :options="$formattedPeople"
                                :selected="old('person_id')"
                                placeholder="جستجوی شخص..."
                            />
                            <p class="text-xs text-gray-500 mt-1">با انتخاب شخص، نیازی به وارد کردن نام طلبکار نیست</p>
                        </div>

                        <!-- مبلغ کل با کامپوننت price-input -->
                        <div>
                            <x-price-input 
                                name="amount"
                                label="مبلغ کل (ریال)"
                                :value="old('amount')"
                                placeholder="مثال: ۱۵۰,۰۰۰,۰۰۰"
                                :min="1000"
                                :required="true"
                            />
                            @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- مبلغ باقی‌مانده با کامپوننت price-input -->
                        <div>
                            <x-price-input 
                                name="remaining_amount"
                                label="مبلغ باقی‌مانده (ریال)"
                                :value="old('remaining_amount')"
                                placeholder="مثال: ۱۵۰,۰۰۰,۰۰۰"
                                :min="0"
                                :required="true"
                            />
                            @error('remaining_amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- تاریخ سررسید (شمسی) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ سررسید <span class="text-red-500">*</span></label>
                            <input type="text" name="due_date" id="due_date" value="{{ old('due_date', $todayJalali ?? '') }}" 
                                   class="jalali-datepicker w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition @error('due_date') border-red-500 @enderror" 
                                   placeholder="مثال: ۱۴۰۲/۱۲/۲۵" autocomplete="off" required>
                            @error('due_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">تاریخ را به فرمت شمسی وارد کنید</p>
                        </div>

                        <!-- وضعیت با کامپوننت searchable-select -->
                        <div>
                            <x-searchable-select 
                                name="status"
                                label="وضعیت"
                                :options="[
                                    ['id' => 'pending', 'text' => 'در انتظار'],
                                    ['id' => 'paid', 'text' => 'پرداخت شده'],
                                    ['id' => 'overdue', 'text' => 'سررسید گذشته']
                                ]"
                                :selected="old('status', 'pending')"
                                placeholder="انتخاب کنید..."
                                required="true"
                            />
                            @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                            <textarea name="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition @error('description') border-red-500 @enderror" 
                                      placeholder="توضیحات اضافی...">{{ old('description') }}</textarea>
                            @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('liabilities.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105">
                            ثبت تعهد
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
    // تقویم شمسی برای تاریخ سررسید
    $('#due_date').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true,
        initialValue: true,
        calendar: {
            persian: true
        }
    });

    // مدیریت خودکار مبلغ باقی‌مانده
    const amountInput = document.querySelector('input[name="amount"]');
    const remainingInput = document.querySelector('input[name="remaining_amount"]');
    
    if (amountInput && remainingInput) {
        const amountHidden = document.getElementById('amount');
        const remainingHidden = document.getElementById('remaining_amount');
        
        // وقتی مبلغ کل تغییر می‌کند
        amountInput.addEventListener('priceChange', function(e) {
            const amount = e.detail.rawValue;
            const remaining = parseInt(remainingHidden.value) || 0;
            
            // اگر باقی‌مانده خالی بود یا از مبلغ کل بیشتر بود
            if (!remainingHidden.value || remaining > amount) {
                // به‌روزرسانی فیلد باقی‌مانده
                const remainingDisplay = document.querySelector('input[name="remaining_amount"]');
                if (remainingDisplay) {
                    remainingDisplay.value = Number(amount).toLocaleString('en-US');
                    remainingHidden.value = amount;
                    
                    // ایجاد رویداد برای اطلاع کامپوننت
                    remainingDisplay.dispatchEvent(new CustomEvent('priceUpdate', {
                        detail: { rawValue: amount }
                    }));
                }
            }
        });
        
        // وقتی مبلغ باقی‌مانده تغییر می‌کند
        remainingInput.addEventListener('priceChange', function(e) {
            const remaining = e.detail.rawValue;
            const amount = parseInt(amountHidden.value) || 0;
            
            // اگر باقی‌مانده از مبلغ کل بیشتر بود
            if (remaining > amount && amount > 0) {
                alert('مبلغ باقی‌مانده نمی‌تواند از مبلغ کل بیشتر باشد.');
                
                // برگرداندن به مقدار قبلی
                remainingInput.value = Number(amount).toLocaleString('en-US');
                remainingHidden.value = amount;
            }
        });
    }

    // اعتبارسنجی قبل از ارسال فرم
    $('#liabilityForm').on('submit', function(e) {
        const amount = parseInt($('#amount').val()) || 0;
        const remaining = parseInt($('#remaining_amount').val()) || 0;
        
        if (remaining > amount) {
            e.preventDefault();
            alert('مبلغ باقی‌مانده نمی‌تواند از مبلغ کل بیشتر باشد.');
            return false;
        }
        
        return true;
    });
});
</script>
@endpush