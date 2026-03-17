@extends('layouts.app')

@section('styles')
<style>
    .select2-container--bootstrap-5 .select2-selection {
        min-height: 42px;
        padding: 0.375rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
    }
    .select2-container--bootstrap-5.select2-container--focus .select2-selection {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.2);
    }
</style>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">ویرایش سرمایه‌گذاری</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('investments.show', $investment) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            نمایش جزئیات
                        </a>
                        <a href="{{ route('investments.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
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

                <!-- خلاصه اطلاعات خودرو -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <span class="text-sm text-gray-600">خودرو:</span>
                        <span class="text-lg font-bold text-blue-600 mr-2">{{ $investment->car->title }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">سرمایه‌گذار:</span>
                        <span class="text-lg font-bold text-green-600 mr-2">{{ $investment->investor->full_name }}</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">قیمت خودرو:</span>
                        <span class="text-lg font-bold text-purple-600 mr-2">{{ number_format($investment->car->purchase_price) }} ریال</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('investments.update', $investment) }}" id="investmentEditForm">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- انتخاب خودرو با کامپوننت searchable-select -->
                        <div>
                            <x-searchable-select 
                                name="car_id"
                                label="انتخاب خودرو"
                                :options="$formattedCars"
                                :selected="old('car_id', $investment->car_id)"
                                placeholder="جستجوی خودرو..."
                                required="true"
                            />
                        </div>

                        <!-- انتخاب سرمایه‌گذار با کامپوننت searchable-select -->
                        <div>
                            <x-searchable-select 
                                name="investor_id"
                                label="انتخاب سرمایه‌گذار"
                                :options="$formattedInvestors"
                                :selected="old('investor_id', $investment->investor_id)"
                                placeholder="جستجوی سرمایه‌گذار..."
                                required="true"
                            />
                        </div>

                        <!-- مبلغ سرمایه‌گذاری با کامپوننت price-input -->
                        <div>
                            <x-price-input 
                                name="amount"
                                label="مبلغ سرمایه‌گذاری (ریال)"
                                :value="old('amount', $investment->amount)"
                                placeholder="مثال: ۵۰,۰۰۰,۰۰۰"
                                :min="1000"
                                :required="true"
                                formId="investmentEditForm"
                            />
                        </div>

                        <!-- تاریخ سرمایه‌گذاری -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ سرمایه‌گذاری <span class="text-red-500">*</span></label>
                            <input type="text" name="investment_date" id="investment_date" 
                                   value="{{ old('investment_date', $investment->jalali_date ?? '') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('investment_date') border-red-500 @enderror"
                                   placeholder="مثال: 1402/12/25" autocomplete="off" required>
                            @error('investment_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- اطلاعات خودرو انتخاب شده -->
                    <div id="car_info" class="mt-6 p-4 bg-purple-50 rounded-lg hidden">
                        <h4 class="font-bold text-md mb-3 text-purple-800">اطلاعات خودرو انتخاب شده:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">عنوان:</span>
                                <span id="car_title" class="font-medium mr-1 block mt-1"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">قیمت:</span>
                                <span id="car_price" class="font-medium mr-1 block mt-1"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">سرمایه‌گذاری شده:</span>
                                <span id="car_invested" class="font-medium mr-1 block mt-1"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">باقی‌مانده (بدون این سرمایه):</span>
                                <span id="car_remaining" class="font-medium mr-1 block mt-1"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">درصد تکمیل:</span>
                                <span id="car_percentage" class="font-medium mr-1 block mt-1"></span>
                            </div>
                        </div>
                    </div>

                    <!-- اطلاعات سرمایه‌گذار انتخاب شده -->
                    <div id="investor_info" class="mt-3 p-4 bg-green-50 rounded-lg hidden">
                        <h4 class="font-bold text-md mb-3 text-green-800">اطلاعات سرمایه‌گذار انتخاب شده:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">نام و نام خانوادگی:</span>
                                <span id="investor_full_name" class="font-medium mr-1 block mt-1"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">کد ملی:</span>
                                <span id="investor_national_code" class="font-medium mr-1 block mt-1"></span>
                            </div>
                            <div>
                                <span class="text-gray-600">تلفن:</span>
                                <span id="investor_phone" class="font-medium mr-1 block mt-1"></span>
                            </div>
                        </div>
                    </div>

                    <!-- نمایش خلاصه سرمایه‌گذاری‌های این خودرو -->
                    <div id="investmentSummary" class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2">خلاصه سرمایه‌گذاری‌های این خودرو</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <span class="text-sm text-gray-600">قیمت خودرو:</span>
                                <span class="text-lg font-bold text-blue-600" id="summaryCarPrice">{{ number_format($investment->car->purchase_price) }} ریال</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">مجموع سرمایه‌گذاری شده:</span>
                                <span class="text-lg font-bold text-green-600" id="summaryTotalInvested">
                                    {{ number_format($investment->car->investments->sum('amount')) }} ریال
                                </span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">مبلغ باقی‌مانده:</span>
                                <span class="text-lg font-bold text-orange-600" id="summaryRemaining">
                                    {{ number_format($investment->car->purchase_price - $investment->car->investments->sum('amount')) }} ریال
                                </span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">درصد تأمین شده:</span>
                                <span class="text-lg font-bold text-purple-600" id="summaryPercentage">
                                    @php
                                        $totalInvested = $investment->car->investments->sum('amount');
                                        $percentage = $investment->car->purchase_price > 0 
                                            ? ($totalInvested / $investment->car->purchase_price) * 100 
                                            : 0;
                                    @endphp
                                    {{ number_format($percentage, 2) }}%
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('investments.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-purple-500 hover:bg-purple-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105">
                            به‌روزرسانی سرمایه‌گذاری
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // تقویم شمسی
    $('#investment_date').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true,
        initialValue: true,
        calendar: {
            persian: true
        }
    });

    // نمایش اطلاعات خودرو اولیه
    setTimeout(function() {
        $('select[name="car_id"]').trigger('change');
        $('select[name="investor_id"]').trigger('change');
    }, 500);
});

// گوش دادن به تغییرات سلکت باکس خودرو
$(document).on('change', 'select[name="car_id"]', function() {
    var selected = $(this).find(':selected');
    var carId = $(this).val();
    
    if (carId) {
        var title = selected.text().split('-')[0].trim();
        var price = selected.data('price');
        var invested = selected.data('invested') || 0;
        var remaining = selected.data('remaining') || 0;
        
        $('#car_title').text(title || '---');
        $('#car_price').text(price ? Number(price).toLocaleString('fa-IR') + ' ریال' : '---');
        $('#car_invested').text(invested ? Number(invested).toLocaleString('fa-IR') + ' ریال' : '0 ریال');
        $('#car_remaining').text(remaining ? Number(remaining).toLocaleString('fa-IR') + ' ریال' : '0 ریال');
        
        if (price > 0) {
            var percentage = ((price - remaining) / price * 100).toFixed(1);
            $('#car_percentage').text(percentage + '%');
        }
        
        $('#car_info').removeClass('hidden');
        updateSummaryFromCar(price, invested, remaining);
    } else {
        $('#car_info').addClass('hidden');
    }
});

// گوش دادن به تغییرات سلکت باکس سرمایه‌گذار
$(document).on('change', 'select[name="investor_id"]', function() {
    var selected = $(this).find(':selected');
    var investorId = $(this).val();
    
    if (investorId) {
        var fullName = selected.text().split('-')[0].trim();
        var nationalCode = selected.data('national-code');
        var phone = selected.data('phone');
        
        $('#investor_full_name').text(fullName || '---');
        $('#investor_national_code').text(nationalCode || '---');
        $('#investor_phone').text(phone || '---');
        
        $('#investor_info').removeClass('hidden');
    } else {
        $('#investor_info').addClass('hidden');
    }
});

function updateSummaryFromCar(price, invested, remaining) {
    $('#summaryCarPrice').text(price ? Number(price).toLocaleString('fa-IR') + ' ریال' : '0 ریال');
    $('#summaryTotalInvested').text(invested ? Number(invested).toLocaleString('fa-IR') + ' ریال' : '0 ریال');
    $('#summaryRemaining').text(remaining ? Number(remaining).toLocaleString('fa-IR') + ' ریال' : '0 ریال');
    
    if (price > 0 && invested > 0) {
        var percentage = (invested / price * 100).toFixed(2);
        $('#summaryPercentage').text(percentage + '%');
    }
}

// قبل از ارسال فرم
$('#investmentEditForm').on('submit', function(e) {
    const amount = document.querySelector('[name="amount"]').value;
    const carId = document.querySelector('select[name="car_id"]').value;
    const investorId = document.querySelector('select[name="investor_id"]').value;
    
    if (!amount || amount == '0') {
        e.preventDefault();
        alert('لطفاً مبلغ سرمایه‌گذاری را وارد کنید');
        return false;
    }
    
    if (!carId) {
        e.preventDefault();
        alert('لطفاً یک خودرو انتخاب کنید');
        return false;
    }
    
    if (!investorId) {
        e.preventDefault();
        alert('لطفاً یک سرمایه‌گذار انتخاب کنید');
        return false;
    }
    
    return true;
});
</script>
@endpush