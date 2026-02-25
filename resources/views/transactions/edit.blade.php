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
                        <!-- مبلغ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">مبلغ (ریال) <span class="text-red-500">*</span></label>
                            <input type="number" name="amount" value="{{ old('amount', $transaction->amount) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                   min="1000" required>
                        </div>

                        <!-- تاریخ تراکنش -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ تراکنش <span class="text-red-500">*</span></label>
                            <input type="date" name="transaction_date" value="{{ old('transaction_date', $transaction->transaction_date) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                        </div>

                        <!-- حساب (شرطی بر اساس نوع) -->
                        @if($transaction->type === 'income')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">حساب مقصد (دریافت به) <span class="text-red-500">*</span></label>
                                <select name="to_account_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                                    <option value="">انتخاب کنید</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('to_account_id', $transaction->to_account_id) == $account->id ? 'selected' : '' }}>
                                            {{ $account->name }} ({{ number_format($account->balance) }} ریال)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">حساب مبدأ (پرداخت از) <span class="text-red-500">*</span></label>
                                <select name="from_account_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                                    <option value="">انتخاب کنید</option>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('from_account_id', $transaction->from_account_id) == $account->id ? 'selected' : '' }}>
                                            {{ $account->name }} ({{ number_format($account->balance) }} ریال)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <!-- شخص مرتبط -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">شخص مرتبط</label>
                            <select name="person_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <option value="">انتخاب کنید</option>
                                @foreach($people as $person)
                                    <option value="{{ $person->id }}" {{ old('person_id', $transaction->person_id) == $person->id ? 'selected' : '' }}>
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
                                    <option value="{{ $method->id }}" {{ old('payment_method_id', $transaction->payment_method_id) == $method->id ? 'selected' : '' }}>
                                        {{ $method->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- شماره چک (نمایش شرطی) -->
                        <div id="check_fields" class="{{ $transaction->paymentMethod && str_contains($transaction->paymentMethod->name, 'چک') ? '' : 'hidden' }} col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">شماره چک</label>
                                <input type="text" name="check_number" value="{{ old('check_number', $transaction->check_number) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ چک</label>
                                <input type="date" name="check_date" value="{{ old('check_date', $transaction->check_date) }}" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                        </div>

                        <!-- دارایی مرتبط -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">دارایی مرتبط</label>
                            <select name="asset_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                                <option value="">انتخاب کنید</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->id }}" {{ old('asset_id', $transaction->asset_id) == $asset->id ? 'selected' : '' }}>
                                        {{ $asset->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- وضعیت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت <span class="text-red-500">*</span></label>
                            <select name="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                                <option value="pending" {{ old('status', $transaction->status) == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                <option value="completed" {{ old('status', $transaction->status) == 'completed' ? 'selected' : '' }}>تکمیل شده</option>
                                <option value="cancelled" {{ old('status', $transaction->status) == 'cancelled' ? 'selected' : '' }}>لغو شده</option>
                            </select>
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
<script>
    // نمایش فیلدهای چک در صورت انتخاب روش پرداخت "چک"
    document.getElementById('payment_method_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const checkFields = document.getElementById('check_fields');
        
        if (selectedOption && selectedOption.text.includes('چک')) {
            checkFields.classList.remove('hidden');
        } else {
            checkFields.classList.add('hidden');
        }
    });
</script>
@endpush