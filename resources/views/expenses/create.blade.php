@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">ثبت هزینه جدید</h2>
                    <a href="{{ route('expenses.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
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

                <form method="POST" action="{{ route('expenses.store') }}" enctype="multipart/form-data" id="expenseForm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- عنوان -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">عنوان <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition @error('title') border-red-500 @enderror" 
                                   placeholder="مثال: صافکاری خودرو" required>
                            @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- مبلغ با کامپوننت price-input -->
                        <div>
                            <x-price-input 
                                name="amount"
                                label="مبلغ"
                                :value="old('amount')"
                                placeholder="مثال: ۵۰۰,۰۰۰"
                                :required="true"
                                :min="1000"
                                formId="expenseForm"
                            />
                        </div>

                        <!-- تاریخ هزینه (شمسی با تقویم) - بدون کامپوننت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ هزینه <span class="text-red-500">*</span></label>
                            <input type="text" name="expense_date" id="expense_date" 
                                   value="{{ old('expense_date', now_jalali('Y/m/d')) }}" 
                                   class="jalali-datepicker w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition @error('expense_date') border-red-500 @enderror" 
                                   placeholder="مثال: ۱۴۰۲/۱۲/۲۵" autocomplete="off" required>
                            @error('expense_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- دسته‌بندی با کامپوننت searchable-select -->
                        <div>
                            <x-searchable-select 
                                name="category"
                                label="دسته‌بندی"
                                :options="$categoryOptions"
                                :selected="old('category')"
                                placeholder="انتخاب کنید..."
                                required="true"
                            />
                        </div>

                        <!-- خودرو مرتبط با کامپوننت searchable-select -->
                        <div>
                            <x-searchable-select 
                                name="car_id"
                                label="خودرو مرتبط"
                                :options="$carOptions"
                                :selected="old('car_id')"
                                placeholder="بدون خودرو (هزینه عمومی)"
                            />
                        </div>

                        <!-- شخص مرتبط با کامپوننت searchable-select -->
                        <div>
                            <x-searchable-select 
                                name="person_id"
                                label="شخص مرتبط"
                                :options="$personOptions"
                                :selected="old('person_id')"
                                placeholder="بدون شخص"
                            />
                        </div>

                        <!-- حساب پرداخت با کامپوننت searchable-select -->
                        <div>
                            <x-searchable-select 
                                name="account_id"
                                label="حساب پرداخت"
                                :options="$accountOptions"
                                :selected="old('account_id')"
                                placeholder="بدون کسر از حساب"
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

                        <!-- تصویر رسید -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">تصویر رسید</label>
                            <input type="file" name="receipt_image" accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition @error('receipt_image') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">فرمت‌های مجاز: jpeg, png, jpg (حداکثر ۲ مگابایت)</p>
                            @error('receipt_image') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- یادداشت‌ها -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">یادداشت‌ها</label>
                            <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                            @error('notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('expenses.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg transition">
                            ثبت هزینه
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
</script>
@endpush