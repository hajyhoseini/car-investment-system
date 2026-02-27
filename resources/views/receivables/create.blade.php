@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">ثبت مطالبه جدید</h2>
                    <a href="{{ route('receivables.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
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

                <form method="POST" action="{{ route('receivables.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- عنوان -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">عنوان <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                                   placeholder="مثال: فروش خودرو" required>
                        </div>

                        <!-- مبلغ (با ویرگول فارسی) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">مبلغ (ریال) <span class="text-red-500">*</span></label>
                            <input type="text" name="amount" id="amount" value="{{ old('amount') }}" 
                                   class="price-format w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                                   placeholder="مثال: ۵,۰۰۰,۰۰۰" required>
                        </div>

                        <!-- نوع مطالبه -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نوع <span class="text-red-500">*</span></label>
                            <select name="currency_type" id="currency_type" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" required>
                                <option value="">انتخاب کنید</option>
                                @foreach($currencyTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('currency_type') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- شخص مرتبط -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">شخص مرتبط</label>
                            <select name="person_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                <option value="">انتخاب کنید</option>
                                @foreach($people as $person)
                                    <option value="{{ $person->id }}" {{ old('person_id') == $person->id ? 'selected' : '' }}>
                                        {{ $person->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- تاریخ مطالبه (شمسی) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ مطالبه <span class="text-red-500">*</span></label>
                            <input type="text" name="receivable_date" id="receivable_date" 
                                   value="{{ old('receivable_date', now_jalali('Y/m/d')) }}" 
                                   class="jalali-datepicker w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                                   placeholder="مثال: ۱۴۰۲/۱۲/۲۵" autocomplete="off" required>
                            <p class="text-xs text-gray-500 mt-1">تاریخ را به فرمت شمسی وارد کنید</p>
                        </div>

                        <!-- تاریخ سررسید (شمسی) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ سررسید</label>
                            <input type="text" name="due_date" id="due_date" value="{{ old('due_date') }}" 
                                   class="jalali-datepicker w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                                   placeholder="مثال: ۱۴۰۲/۱۲/۲۵" autocomplete="off">
                            <p class="text-xs text-gray-500 mt-1">تاریخ را به فرمت شمسی وارد کنید</p>
                        </div>

                        <!-- وضعیت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت <span class="text-red-500">*</span></label>
                            <select name="status" id="status" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                <option value="partially_paid" {{ old('status') == 'partially_paid' ? 'selected' : '' }}>پرداخت جزئی</option>
                                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>تسویه شده</option>
                            </select>
                        </div>

                        <!-- مبلغ پرداخت شده (برای وضعیت پرداخت جزئی) -->
                        <div id="paid_amount_field" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">مبلغ پرداخت شده (ریال)</label>
                            <input type="text" name="paid_amount" id="paid_amount" value="{{ old('paid_amount') }}" 
                                   class="price-format w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                                   placeholder="مثال: ۲,۰۰۰,۰۰۰">
                        </div>

                        <!-- فیلدهای اختصاصی بر اساس نوع -->
                        <div id="check_fields" class="hidden col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">شماره چک</label>
                                <input type="text" name="currency_details[check_number]" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">نام بانک</label>
                                <input type="text" name="currency_details[bank_name]" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ چک</label>
                                <input type="text" name="currency_details[check_date]" id="check_date"
                                       class="jalali-datepicker w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500" 
                                       placeholder="مثال: ۱۴۰۲/۱۲/۲۵" autocomplete="off">
                            </div>
                        </div>

                        <div id="gold_fields" class="hidden col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">وزن (گرم)</label>
                                <input type="number" step="0.01" name="currency_details[weight]" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">عیار</label>
                                <input type="text" name="currency_details[karat]" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                                <input type="text" name="currency_details[description]" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                        </div>

                        <div id="dollar_fields" class="hidden col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">نرخ ارز (ریال)</label>
                                <input type="text" name="currency_details[exchange_rate]" 
                                       class="price-format w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500" 
                                       placeholder="مثال: ۶۰۰,۰۰۰">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                                <input type="text" name="currency_details[description]" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                        </div>

                        <!-- فایل ضمیمه -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">فایل ضمیمه (تصویر چک/سند)</label>
                            <input type="file" name="attachments" accept="image/*,application/pdf"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            <p class="text-xs text-gray-500 mt-1">فرمت‌های مجاز: jpeg, png, jpg, pdf (حداکثر ۲ مگابایت)</p>
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">{{ old('description') }}</textarea>
                        </div>

                        <!-- یادداشت‌ها -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">یادداشت‌ها</label>
                            <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2">
                        <a href="{{ route('receivables.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-purple-500 hover:bg-purple-600 text-white font-bold rounded-xl shadow-lg transition">
                            ثبت مطالبه
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

        // فرمت عدد با ویرگول
        $('.price-format').on('input', function() {
            let value = this.value.replace(/[^\d]/g, '');
            if (value) {
                this.value = Number(value).toLocaleString('en-US');
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
        
        if (status === 'partially_paid') {
            paidField.classList.remove('hidden');
        } else {
            paidField.classList.add('hidden');
        }
    });
</script>
@endpush