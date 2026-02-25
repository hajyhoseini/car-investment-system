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
                        <!-- مبلغ - با کامپوننت جدید -->
                        <x-price-input 
                            name="amount"
                            label="مبلغ (ریال)"
                            :value="old('amount')"
                            placeholder="مثال: ۱,۰۰۰,۰۰۰"
                            :min="1000"
                            :required="true"
                        />

                        <!-- تاریخ تراکنش -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ تراکنش <span class="text-red-500">*</span></label>
                            <input type="date" name="transaction_date" value="{{ old('transaction_date', now()->format('Y-m-d')) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                        </div>

                        <!-- حساب (شرطی بر اساس نوع) -->
                        @if($type === 'income')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">حساب مقصد (دریافت به) <span class="text-red-500">*</span></label>
                                <select name="to_account_id" id="account_select" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                                    <option value="">انتخاب کنید</option>
                                    @foreach($accounts as $account)
                                       <option value="{{ $account->id }}" {{ old('to_account_id') == $account->id ? 'selected' : '' }}
                                            data-balance="{{ $account->current_balance }}">
                                            {{ $account->name }} (موجودی: {{ number_format($account->current_balance) }} ریال)
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1" id="balance_preview">
                                    موجودی پس از دریافت: <span id="balance_after">0</span> ریال
                                </p>
                            </div>
                        @else
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">حساب مبدأ (پرداخت از) <span class="text-red-500">*</span></label>
                                <select name="from_account_id" id="account_select" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                                    <option value="">انتخاب کنید</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('from_account_id') == $account->id ? 'selected' : '' }}
                                                data-balance="{{ $account->current_balance }}">
                                            {{ $account->name }} (موجودی: {{ number_format($account->current_balance) }} ریال)
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1" id="balance_preview">
                                    موجودی پس از پرداخت: <span id="balance_after">0</span> ریال
                                </p>
                            </div>
                        @endif

                        <!-- شخص مرتبط -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">شخص مرتبط</label>
                            <select name="person_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <option value="">انتخاب کنید</option>
                                @foreach($people as $person)
                                    <option value="{{ $person->id }}" {{ old('person_id') == $person->id ? 'selected' : '' }}>
                                        {{ $person->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- روش پرداخت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">روش پرداخت</label>
                            <select name="payment_method_id" id="payment_method_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <option value="">انتخاب کنید</option>
                                @foreach($paymentMethods as $method)
                                    <option value="{{ $method->id }}" {{ old('payment_method_id') == $method->id ? 'selected' : '' }}>
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- شماره چک (نمایش شرطی) -->
                        <div id="check_fields" class="hidden col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">شماره چک</label>
                                <input type="text" name="check_number" value="{{ old('check_number') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ چک</label>
                                <input type="date" name="check_date" value="{{ old('check_date') }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                        </div>

                        <!-- دارایی مرتبط -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">دارایی مرتبط</label>
                            <select name="asset_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <option value="">انتخاب کنید</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                        {{ $asset->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- وضعیت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت <span class="text-red-500">*</span></label>
                            <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>لغو شده</option>
                            </select>
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
<script>
    // نمایش فیلدهای چک در صورت انتخاب روش پرداخت "چک"
    document.getElementById('payment_method_id')?.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const checkFields = document.getElementById('check_fields');
        
        if (selectedOption && selectedOption.text.includes('چک')) {
            checkFields.classList.remove('hidden');
        } else {
            checkFields.classList.add('hidden');
        }
    });

    // محاسبه موجودی پس از تراکنش - آپدیت شده برای کامپوننت جدید
    document.addEventListener('DOMContentLoaded', function() {
        // پیدا کردن فیلد مبلغ (مهم: فیلد اصلی با کلاس price-input)
        const amountInput = document.querySelector('.price-input');
        const accountSelect = document.getElementById('account_select');
        const balanceAfterSpan = document.getElementById('balance_after');
        const transactionType = '{{ $type }}';
        
        function updateBalanceAfter() {
            if (accountSelect && amountInput && balanceAfterSpan) {
                const selectedOption = accountSelect.options[accountSelect.selectedIndex];
                const currentBalance = parseFloat(selectedOption?.dataset.balance || 0);
                
                // گرفتن مقدار عددی خالص از فیلد قیمت
                let rawAmount = amountInput.value.replace(/[^0-9]/g, '');
                const amount = parseFloat(rawAmount || 0);
                
                let balanceAfter = currentBalance;
                if (transactionType === 'income') {
                    balanceAfter = currentBalance + amount;
                } else {
                    balanceAfter = currentBalance - amount;
                }
                
                balanceAfterSpan.textContent = new Intl.NumberFormat('fa-IR').format(balanceAfter);
            }
        }
        
        // رویداد برای فیلد مبلغ (با تأخیر برای هماهنگی با کامپوننت)
        if (amountInput) {
            amountInput.addEventListener('input', function() {
                setTimeout(updateBalanceAfter, 10);
            });
        }
        
        if (accountSelect) {
            accountSelect.addEventListener('change', updateBalanceAfter);
        }
        
        // اجرای اولیه با کمی تأخیر
        setTimeout(updateBalanceAfter, 100);
    });
</script>
@endpush