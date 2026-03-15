{{-- resources/views/receivables/edit.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">ویرایش مطالبه</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('receivables.show', $receivable) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            مشاهده جزئیات
                        </a>
                        <a href="{{ route('receivables.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
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

                <!-- اطلاعات خلاصه -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold mb-3">خلاصه اطلاعات</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <span class="text-sm text-gray-600">عنوان:</span>
                            <div class="font-bold">{{ $receivable->title }}</div>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">نوع:</span>
                            <div class="font-bold text-purple-600">{{ $receivable->currency_type_label }}</div>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">وضعیت:</span>
                            <div>
                                <span class="px-2 py-1 
                                    @if($receivable->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($receivable->status == 'partially_paid') bg-blue-100 text-blue-800
                                    @elseif($receivable->status == 'paid') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif rounded-full text-xs">
                                    {{ $receivable->status_label }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('receivables.update', $receivable) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- عنوان -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">عنوان <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $receivable->title) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('title') border-red-500 @enderror" 
                                   placeholder="مثال: فروش خودرو" required>
                            @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- مبلغ -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">مبلغ <span class="text-red-500">*</span></label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount', $receivable->amount) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('amount') border-red-500 @enderror" 
                                   placeholder="مثال: 5000000" min="1000" required>
                            @error('amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- نوع مطالبه -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نوع <span class="text-red-500">*</span></label>
                            <select name="currency_type" id="currency_type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('currency_type') border-red-500 @enderror" required>
                                <option value="">انتخاب کنید</option>
                                @foreach($currencyTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('currency_type', $receivable->currency_type) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('currency_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- شخص مرتبط -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">شخص مرتبط</label>
                            <select name="person_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('person_id') border-red-500 @enderror">
                                <option value="">بدون شخص</option>
                                @foreach($formattedPeople as $person)
                                    <option value="{{ $person['id'] }}" {{ old('person_id', $receivable->person_id) == $person['id'] ? 'selected' : '' }}>
                                        {{ $person['text'] }}
                                        @if(!empty($person['subtext']))
                                            <span class="text-gray-500 text-xs">({{ $person['subtext'] }})</span>
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('person_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- تاریخ مطالبه (شمسی) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ مطالبه <span class="text-red-500">*</span></label>
                            <input type="text" name="receivable_date" id="receivable_date" 
                                   value="{{ old('receivable_date', $receivable->receivable_date ? jalali_date($receivable->receivable_date) : '') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('receivable_date') border-red-500 @enderror jalali-datepicker"
                                   placeholder="مثال: ۱۴۰۲/۱۲/۲۵" autocomplete="off" required>
                            @error('receivable_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- تاریخ سررسید (شمسی) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ سررسید</label>
                            <input type="text" name="due_date" id="due_date" 
                                   value="{{ old('due_date', $receivable->due_date ? jalali_date($receivable->due_date) : '') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('due_date') border-red-500 @enderror jalali-datepicker"
                                   placeholder="مثال: ۱۴۰۲/۱۲/۲۵" autocomplete="off">
                            @error('due_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- وضعیت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت <span class="text-red-500">*</span></label>
                            <select name="status" id="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('status') border-red-500 @enderror" required>
                                <option value="pending" {{ old('status', $receivable->status) == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                <option value="partially_paid" {{ old('status', $receivable->status) == 'partially_paid' ? 'selected' : '' }}>پرداخت جزئی</option>
                                <option value="paid" {{ old('status', $receivable->status) == 'paid' ? 'selected' : '' }}>تسویه شده</option>
                            </select>
                            @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- مبلغ پرداخت شده (برای وضعیت پرداخت جزئی) -->
                        <div id="paid_amount_field" class="{{ old('status', $receivable->status) == 'partially_paid' ? '' : 'hidden' }}">
                            <label class="block text-sm font-medium text-gray-700 mb-2">مبلغ پرداخت شده</label>
                            <input type="number" name="paid_amount" id="paid_amount" value="{{ old('paid_amount', $receivable->paid_amount) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('paid_amount') border-red-500 @enderror" 
                                   min="0" max="{{ $receivable->amount }}">
                            @error('paid_amount') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- فیلدهای اختصاصی بر اساس نوع -->
                        <div id="check_fields" class="hidden col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">شماره چک</label>
                                <input type="text" name="currency_details[check_number]" value="{{ old('currency_details.check_number', $receivable->currency_details['check_number'] ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">نام بانک</label>
                                <input type="text" name="currency_details[bank_name]" value="{{ old('currency_details.bank_name', $receivable->currency_details['bank_name'] ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ چک</label>
                                <input type="text" name="currency_details[check_date]" id="check_date" 
                                       value="{{ old('currency_details.check_date', isset($receivable->currency_details['check_date']) ? jalali_date($receivable->currency_details['check_date']) : '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 jalali-datepicker"
                                       placeholder="مثال: ۱۴۰۲/۱۲/۲۵" autocomplete="off">
                            </div>
                        </div>

                        <div id="gold_fields" class="hidden col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">وزن (گرم)</label>
                                <input type="number" step="0.01" name="currency_details[weight]" value="{{ old('currency_details.weight', $receivable->currency_details['weight'] ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">عیار</label>
                                <input type="text" name="currency_details[karat]" value="{{ old('currency_details.karat', $receivable->currency_details['karat'] ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                                <input type="text" name="currency_details[description]" value="{{ old('currency_details.description', $receivable->currency_details['description'] ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                        </div>

                        <div id="dollar_fields" class="hidden col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">نرخ ارز (ریال)</label>
                                <input type="number" name="currency_details[exchange_rate]" value="{{ old('currency_details.exchange_rate', $receivable->currency_details['exchange_rate'] ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                                <input type="text" name="currency_details[description]" value="{{ old('currency_details.description', $receivable->currency_details['description'] ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                        </div>

                        <!-- فایل ضمیمه -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">فایل ضمیمه (تصویر چک/سند)</label>
                            <input type="file" name="attachments" accept="image/*,application/pdf"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 transition @error('attachments') border-red-500 @enderror">
                            @if($receivable->attachments)
                                <p class="text-xs text-gray-500 mt-1">فایل فعلی: <a href="{{ asset('storage/' . $receivable->attachments) }}" target="_blank" class="text-blue-600 hover:underline">مشاهده</a></p>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">فرمت‌های مجاز: jpeg, png, jpg, pdf (حداکثر ۲ مگابایت)</p>
                            @error('attachments') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 transition @error('description') border-red-500 @enderror">{{ old('description', $receivable->description) }}</textarea>
                            @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- یادداشت‌ها -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">یادداشت‌ها</label>
                            <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 transition @error('notes') border-red-500 @enderror">{{ old('notes', $receivable->notes) }}</textarea>
                            @error('notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- هشدار تغییر اطلاعات -->
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <p class="text-sm text-yellow-800 font-medium">تغییر اطلاعات مطالبه</p>
                                <p class="text-xs text-yellow-700 mt-1">
                                    با تغییر مبلغ یا پرداخت، مبلغ باقی‌مانده و وضعیت به‌طور خودکار به‌روزرسانی خواهد شد.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2">
                        <a href="{{ route('receivables.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-purple-500 hover:bg-purple-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105">
                            به‌روزرسانی مطالبه
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
        // تقویم شمسی برای همه فیلدهای تاریخ
        $('.jalali-datepicker').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: true,
            calendar: {
                persian: true
            }
        });
    });

    // نمایش/مخفی کردن فیلدهای اختصاصی بر اساس نوع
    document.getElementById('currency_type').addEventListener('change', function() {
        const type = this.value;
        
        // مخفی کردن همه فیلدها
        document.getElementById('check_fields')?.classList.add('hidden');
        document.getElementById('gold_fields')?.classList.add('hidden');
        document.getElementById('dollar_fields')?.classList.add('hidden');
        
        // نمایش فیلد مربوطه
        if (type === 'check') {
            document.getElementById('check_fields').classList.remove('hidden');
        } else if (type === 'gold') {
            document.getElementById('gold_fields').classList.remove('hidden');
        } else if (type === 'dollar') {
            document.getElementById('dollar_fields').classList.remove('hidden');
        }
    });

    // نمایش/مخفی کردن فیلد مبلغ پرداخت شده بر اساس وضعیت
    document.getElementById('status').addEventListener('change', function() {
        const status = this.value;
        const paidField = document.getElementById('paid_amount_field');
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        
        if (status === 'partially_paid') {
            paidField.classList.remove('hidden');
            document.getElementById('paid_amount').max = amount;
        } else {
            paidField.classList.add('hidden');
        }
    });

    // تنظیم max برای paid_amount بر اساس amount
    document.getElementById('amount').addEventListener('input', function() {
        const amount = parseFloat(this.value) || 0;
        const paidInput = document.getElementById('paid_amount');
        if (paidInput) {
            paidInput.max = amount;
        }
    });

    // محاسبه خودکار مبلغ باقی‌مانده
    document.getElementById('paid_amount').addEventListener('input', function() {
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        const paid = parseFloat(this.value) || 0;
        const remaining = amount - paid;
        
        // می‌تونی اینجا یه فیلد نمایشی برای باقی‌مانده اضافه کنی
    });

    // اجرای اولیه برای نمایش فیلدهای مربوطه
    window.addEventListener('load', function() {
        // نمایش فیلدهای نوع ارز
        const currencyType = document.querySelector('select[name="currency_type"]').value;
        if (currencyType === 'check') {
            document.getElementById('check_fields').classList.remove('hidden');
        } else if (currencyType === 'gold') {
            document.getElementById('gold_fields').classList.remove('hidden');
        } else if (currencyType === 'dollar') {
            document.getElementById('dollar_fields').classList.remove('hidden');
        }

        // نمایش فیلد پرداخت شده
        const status = document.querySelector('select[name="status"]').value;
        if (status === 'partially_paid') {
            document.getElementById('paid_amount_field').classList.remove('hidden');
        }
    });
</script>
@endpush