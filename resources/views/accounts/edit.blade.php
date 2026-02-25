@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">ویرایش حساب: {{ $account->name }}</h2>
                    <a href="{{ route('accounts.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
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

                <form method="POST" action="{{ route('accounts.update', $account) }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- نام حساب -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نام حساب <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $account->name) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                        </div>

                        <!-- نوع حساب -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نوع حساب <span class="text-red-500">*</span></label>
                            <select name="type" id="type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                                <option value="bank" {{ old('type', $account->type) == 'bank' ? 'selected' : '' }}>بانکی</option>
                                <option value="cash" {{ old('type', $account->type) == 'cash' ? 'selected' : '' }}>صندوق</option>
                                <option value="wallet" {{ old('type', $account->type) == 'wallet' ? 'selected' : '' }}>کیف پول</option>
                            </select>
                        </div>

                        <!-- نام بانک (نمایش شرطی) -->
                        <div id="bank_name_field" class="{{ old('type', $account->type) == 'bank' ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-2">نام بانک</label>
                            <input type="text" name="bank_name" value="{{ old('bank_name', $account->bank_name) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        </div>

                        <!-- شماره حساب -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">شماره حساب</label>
                            <input type="text" name="account_number" value="{{ old('account_number', $account->account_number) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        </div>

                        <!-- شماره کارت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">شماره کارت</label>
                            <input type="text" name="card_number" value="{{ old('card_number', $account->card_number) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" maxlength="20">
                        </div>

                        <!-- شماره شبا -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">شماره شبا</label>
                            <input type="text" name="sheba_number" value="{{ old('sheba_number', $account->sheba_number) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" maxlength="30">
                        </div>

                        <!-- موجودی -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">موجودی (ریال) <span class="text-red-500">*</span></label>
                            <input type="number" name="balance" value="{{ old('balance', $account->balance) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" min="0" required>
                        </div>

                        <!-- واحد پول -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">واحد پول <span class="text-red-500">*</span></label>
                            <select name="currency" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" required>
                                <option value="IRR" {{ old('currency', $account->currency) == 'IRR' ? 'selected' : '' }}>ریال</option>
                                <option value="USD" {{ old('currency', $account->currency) == 'USD' ? 'selected' : '' }}>دلار</option>
                                <option value="EUR" {{ old('currency', $account->currency) == 'EUR' ? 'selected' : '' }}>یورو</option>
                            </select>
                        </div>

                        <!-- وضعیت فعال -->
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $account->is_active) ? 'checked' : '' }}
                                   class="ml-2 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="is_active" class="text-sm font-medium text-gray-700">فعال باشد</label>
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">{{ old('description', $account->description) }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2">
                        <a href="{{ route('accounts.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-green-500 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg transition">
                            بروزرسانی حساب
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
    document.getElementById('type').addEventListener('change', function() {
        const bankField = document.getElementById('bank_name_field');
        if (this.value === 'bank') {
            bankField.classList.remove('hidden');
        } else {
            bankField.classList.add('hidden');
        }
    });
</script>
@endpush