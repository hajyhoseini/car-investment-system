@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">
                        ویرایش تراکنش: {{ $transaction->transaction_number }}
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

                <form method="POST" action="{{ route('transactions.update', $transaction) }}" id="transactionForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="type" value="{{ $transaction->type }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- مبلغ با کامپوننت price-input -->
                        <x-price-input 
                            name="amount"
                            label="مبلغ (ریال)"
                            :value="old('amount', $transaction->amount)"
                            placeholder="مثال: ۱,۰۰۰,۰۰۰"
                            :min="1000"
                            :required="true"
                            formId="transactionForm"
                        />

                        <!-- تاریخ تراکنش (شمسی) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ تراکنش <span class="text-red-500">*</span></label>
                            <input type="text" name="transaction_date" id="transaction_date" 
                                   value="{{ old('transaction_date', $transaction->transaction_date ? jalali_date($transaction->transaction_date) : '') }}" 
                                   class="jalali-datepicker w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('transaction_date') border-red-500 @enderror"
                                   placeholder="مثال: ۱۴۰۲/۱۲/۲۵" autocomplete="off" required>
                            @error('transaction_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- حساب (شرطی بر اساس نوع) با کامپوننت searchable-select -->
                        @if($transaction->type === 'income')
                            <div>
                                <x-searchable-select 
                                    name="to_asset_id"
                                    label="حساب مقصد (دریافت به)"
                                    :options="$accountOptions"
                                    :selected="old('to_asset_id', $transaction->to_asset_id)"
                                    placeholder="انتخاب کنید..."
                                    required="true"
                                />
                            </div>
                        @else
                            <div>
                                <x-searchable-select 
                                    name="from_asset_id"
                                    label="حساب مبدأ (پرداخت از)"
                                    :options="$accountOptions"
                                    :selected="old('from_asset_id', $transaction->from_asset_id)"
                                    placeholder="انتخاب کنید..."
                                    required="true"
                                />
                            </div>
                        @endif

                        <!-- شخص مرتبط با کامپوننت searchable-select -->
                        <div>
                            <x-searchable-select 
                                name="person_id"
                                label="شخص مرتبط"
                                :options="$personOptions"
                                :selected="old('person_id', $transaction->person_id)"
                                placeholder="انتخاب کنید..."
                            />
                        </div>

                        <!-- روش پرداخت با کامپوننت searchable-select -->
                        <div>
                            <x-searchable-select 
                                name="payment_method_id"
                                label="روش پرداخت"
                                :options="$paymentMethodOptions"
                                :selected="old('payment_method_id', $transaction->payment_method_id)"
                                placeholder="انتخاب کنید..."
                            />
                        </div>

                        <!-- شماره چک (نمایش شرطی) -->
                        <div id="check_fields" class="{{ $transaction->paymentMethod && str_contains($transaction->paymentMethod->name, 'چک') ? '' : 'hidden' }} col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">شماره چک</label>
                                <input type="text" name="check_number" value="{{ old('check_number', $transaction->check_number) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ چک (شمسی)</label>
                                <input type="text" name="check_date" id="check_date" 
                                       value="{{ old('check_date', $transaction->check_date ? jalali_date($transaction->check_date) : '') }}" 
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
                                :selected="old('asset_id', $transaction->asset_id)"
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
                                :selected="old('status', $transaction->status)"
                                placeholder="انتخاب کنید..."
                                required="true"
                            />
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                            <input type="text" name="description" value="{{ old('description', $transaction->description) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        </div>

                        <!-- یادداشت‌ها -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">یادداشت‌ها</label>
                            <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ old('notes', $transaction->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2">
                        <a href="{{ route('transactions.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-green-500 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg transition">
                            بروزرسانی تراکنش
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
</script>
@endpush