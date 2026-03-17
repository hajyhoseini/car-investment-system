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

                <form method="POST" action="{{ route('receivables.store') }}" enctype="multipart/form-data" id="receivableForm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- عنوان -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">عنوان <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                                   placeholder="مثال: فروش خودرو" required>
                        </div>

                        <!-- مبلغ با کامپوننت price-input -->
                        <div>
                            <x-price-input 
                                name="amount"
                                label="مبلغ"
                                :value="old('amount')"
                                placeholder="مثال: ۵,۰۰۰,۰۰۰"
                                :required="true"
                                :min="1000"
                                formId="receivableForm"
                            />
                        </div>

                        <!-- نوع مطالبه با کامپوننت searchable-select -->
                        <div>
                            <x-searchable-select 
                                name="currency_type"
                                label="نوع"
                                :options="$currencyTypeOptions"
                                :selected="old('currency_type')"
                                placeholder="انتخاب کنید..."
                                required="true"
                            />
                        </div>

                        <!-- شخص مرتبط با کامپوننت searchable-select -->
                        <x-searchable-select 
                            name="person_id"
                            label="شخص مرتبط"
                            :options="$formattedPeople"
                            :selected="old('person_id')"
                            placeholder="جستجوی شخص..."
                        />

                        <!-- تاریخ مطالبه -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ مطالبه <span class="text-red-500">*</span></label>
                            <input type="text" name="receivable_date" id="receivable_date" 
                                   value="{{ old('receivable_date', now_jalali('Y/m/d')) }}" 
                                   class="jalali-datepicker w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                                   placeholder="مثال: ۱۴۰۲/۱۲/۲۵" autocomplete="off" required>
                        </div>

                        <!-- تاریخ سررسید -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ سررسید</label>
                            <input type="text" name="due_date" id="due_date" value="{{ old('due_date') }}" 
                                   class="jalali-datepicker w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition" 
                                   placeholder="مثال: ۱۴۰۲/۱۲/۲۵" autocomplete="off">
                        </div>

                        <!-- وضعیت با کامپوننت searchable-select -->
                        <div>
                            <x-searchable-select 
                                name="status"
                                label="وضعیت"
                                :options="[
                                    ['id' => 'pending', 'text' => 'در انتظار'],
                                    ['id' => 'partially_paid', 'text' => 'پرداخت جزئی'],
                                    ['id' => 'paid', 'text' => 'تسویه شده']
                                ]"
                                :selected="old('status', 'pending')"
                                placeholder="انتخاب کنید..."
                                required="true"
                            />
                        </div>

                        <!-- مبلغ پرداخت شده با کامپوننت price-input -->
                        <div id="paid_amount_field" class="hidden">
                            <x-price-input 
                                name="paid_amount"
                                label="مبلغ پرداخت شده"
                                :value="old('paid_amount')"
                                placeholder="مثال: ۲,۰۰۰,۰۰۰"
                                :min="0"
                                formId="receivableForm"
                            />
                        </div>

                        <!-- فیلدهای اختصاصی بر اساس نوع -->
                        <div id="check_fields" class="hidden col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">شماره چک</label>
                                <input type="text" name="currency_details[check_number]" value="{{ old('currency_details.check_number') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">نام بانک</label>
                                <input type="text" name="currency_details[bank_name]" value="{{ old('currency_details.bank_name') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ چک</label>
                                <input type="text" name="currency_details[check_date]" id="check_date" 
                                       value="{{ old('currency_details.check_date') }}"
                                       class="jalali-datepicker w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500"
                                       placeholder="مثال: ۱۴۰۲/۱۲/۲۵" autocomplete="off">
                            </div>
                        </div>

                        <div id="gold_fields" class="hidden col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">وزن (گرم)</label>
                                <input type="number" step="0.01" name="currency_details[weight]" value="{{ old('currency_details.weight') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">عیار</label>
                                <input type="text" name="currency_details[karat]" value="{{ old('currency_details.karat') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                                <input type="text" name="currency_details[description]" value="{{ old('currency_details.description') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                        </div>

                        <div id="dollar_fields" class="hidden col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">نرخ ارز (ریال)</label>
                                <x-price-input 
                                    name="currency_details[exchange_rate]"
                                    label=""
                                    :value="old('currency_details.exchange_rate')"
                                    placeholder="مثال: ۵۰,۰۰۰"
                                    :min="0"
                                    formId="receivableForm"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                                <input type="text" name="currency_details[description]" value="{{ old('currency_details.description') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                            </div>
                        </div>

                        <!-- فایل ضمیمه -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">فایل ضمیمه (تصویر چک/سند)</label>
                            <input type="file" name="attachments" accept="image/*,application/pdf"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 transition">
                            <p class="text-xs text-gray-500 mt-1">فرمت‌های مجاز: jpeg, png, jpg, pdf (حداکثر ۲ مگابایت)</p>
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 transition">{{ old('description') }}</textarea>
                        </div>

                        <!-- یادداشت‌ها -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">یادداشت‌ها</label>
                            <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 transition">{{ old('notes') }}</textarea>
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

@push('scripts')
<script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">

<script>
    $(document).ready(function() {
        $('.jalali-datepicker').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: true,
            calendar: {
                persian: true
            }
        });
    });

    // گوش دادن به تغییرات نوع مطالبه
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.querySelector('[x-data*="currency_type"]');
        const statusSelect = document.querySelector('[x-data*="status"]');
        
        function updateFieldsByType() {
            if (!typeSelect || !typeSelect.__x) return;
            
            const typeData = typeSelect.__x.$data;
            const type = typeData.selectedValue;
            
            document.getElementById('check_fields')?.classList.add('hidden');
            document.getElementById('gold_fields')?.classList.add('hidden');
            document.getElementById('dollar_fields')?.classList.add('hidden');
            
            if (type === 'check') {
                document.getElementById('check_fields')?.classList.remove('hidden');
            } else if (type === 'gold') {
                document.getElementById('gold_fields')?.classList.remove('hidden');
            } else if (type === 'dollar') {
                document.getElementById('dollar_fields')?.classList.remove('hidden');
            }
        }

        function updatePaidFieldByStatus() {
            if (!statusSelect || !statusSelect.__x) return;
            
            const statusData = statusSelect.__x.$data;
            const status = statusData.selectedValue;
            const paidField = document.getElementById('paid_amount_field');
            
            if (status === 'partially_paid') {
                paidField.classList.remove('hidden');
            } else {
                paidField.classList.add('hidden');
            }
        }

        if (typeSelect) {
            typeSelect.addEventListener('change', function() {
                setTimeout(updateFieldsByType, 100);
            });
            setTimeout(updateFieldsByType, 200);
        }

        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                setTimeout(updatePaidFieldByStatus, 100);
            });
            setTimeout(updatePaidFieldByStatus, 200);
        }
    });
</script>
@endpush
@endsection