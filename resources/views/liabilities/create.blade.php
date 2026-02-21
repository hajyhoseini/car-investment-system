@extends('layouts.app')

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

                <form method="POST" action="{{ route('liabilities.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- نوع تعهد -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نوع تعهد <span class="text-red-500">*</span></label>
                            <select name="type" id="type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition @error('type') border-red-500 @enderror" required>
                                <option value="">انتخاب کنید</option>
                                <option value="debt" {{ old('type') == 'debt' ? 'selected' : '' }}>بدهی</option>
                                <option value="check" {{ old('type') == 'check' ? 'selected' : '' }}>چک</option>
                                <option value="installment" {{ old('type') == 'installment' ? 'selected' : '' }}>قسط</option>
                            </select>
                            @error('type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- نام طلبکار -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نام طلبکار <span class="text-red-500">*</span></label>
                            <input type="text" name="creditor_name" value="{{ old('creditor_name') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition @error('creditor_name') border-red-500 @enderror" 
                                   placeholder="مثال: بانک ملی" required>
                            @error('creditor_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- مبلغ کل -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">مبلغ کل (ریال) <span class="text-red-500">*</span></label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition @error('amount') border-red-500 @enderror" 
                                   placeholder="مثال: 150000000" min="0" required>
                            @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- مبلغ باقی‌مانده -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">مبلغ باقی‌مانده (ریال) <span class="text-red-500">*</span></label>
                            <input type="number" name="remaining_amount" id="remaining_amount" value="{{ old('remaining_amount') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition @error('remaining_amount') border-red-500 @enderror" 
                                   placeholder="مثال: 150000000" min="0" required>
                            @error('remaining_amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- تاریخ سررسید (شمسی) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ سررسید <span class="text-red-500">*</span></label>
                            <input type="text" name="due_date" id="due_date" value="{{ old('due_date', $todayJalali ?? '') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition @error('due_date') border-red-500 @enderror" 
                                   placeholder="مثال: 1402/12/25" autocomplete="off" required>
                            @error('due_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">تاریخ را به فرمت شمسی وارد کنید (مثال: 1402/12/25)</p>
                        </div>

                        <!-- وضعیت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت <span class="text-red-500">*</span></label>
                            <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition @error('status') border-red-500 @enderror" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>پرداخت شده</option>
                                <option value="overdue" {{ old('status') == 'overdue' ? 'selected' : '' }}>سررسید گذشته</option>
                            </select>
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
<script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">

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
    $('#amount').on('input', function() {
        const amount = parseFloat($(this).val()) || 0;
        const remaining = $('#remaining_amount');
        
        // اگر remaining_amount خالی بود یا از مقدار کل بیشتر بود
        if (!remaining.val() || parseFloat(remaining.val()) > amount) {
            remaining.val(amount);
        }
    });

    // اطمینان از اینکه remaining_amount بیشتر از amount نباشه
    $('#remaining_amount').on('input', function() {
        const amount = parseFloat($('#amount').val()) || 0;
        const remaining = parseFloat($(this).val()) || 0;
        
        if (remaining > amount) {
            $(this).val(amount);
            alert('مبلغ باقی‌مانده نمی‌تواند از مبلغ کل بیشتر باشد.');
        }
    });
});
</script>
@endpush