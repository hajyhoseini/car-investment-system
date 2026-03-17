{{-- resources/views/car-sales/edit.blade.php --}}

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
        border-color: #f97316;
        box-shadow: 0 0 0 2px rgba(249, 115, 22, 0.2);
    }
</style>
@endsection

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">ویرایش اطلاعات فروش</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('car-sales.show', $carSale) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            مشاهده جزئیات
                        </a>
                        <a href="{{ route('car-sales.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
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

                <!-- اطلاعات خودرو -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold mb-3">اطلاعات خودرو</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <span class="text-sm text-gray-600">خودرو:</span>
                            <div class="font-bold">{{ $carSale->car->title }}</div>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">قیمت خرید:</span>
                            <div class="font-bold text-blue-600">{{ number_format($carSale->car->purchase_price) }} ریال</div>
                        </div>
                        <div>
                            <span class="text-sm text-gray-600">وضعیت فعلی:</span>
                            <div>
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">
                                    فروخته شده
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- لیست سرمایه‌گذاران این خودرو -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">سرمایه‌گذاران این خودرو</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 bg-gray-50 rounded-lg">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-600">سرمایه‌گذار</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-600">مبلغ سرمایه‌گذاری</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-600">درصد مشارکت</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($carSale->car->investments as $investment)
                                <tr>
                                    <td class="px-4 py-2">{{ $investment->investor->full_name }}</td>
                                    <td class="px-4 py-2">{{ number_format($investment->amount) }} ریال</td>
                                    <td class="px-4 py-2">{{ number_format($investment->percentage, 2) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <form method="POST" action="{{ route('car-sales.update', $carSale) }}" id="saleEditForm">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- قیمت فروش با کامپوننت price-input -->
                        <div>
                            <x-price-input 
                                name="selling_price"
                                label="قیمت فروش (ریال)"
                                :value="old('selling_price', $carSale->selling_price)"
                                placeholder="مثال: ۱۵,۰۰۰,۰۰۰,۰۰۰"
                                :required="true"
                                :min="$carSale->car->purchase_price"
                                currency="ریال"
                                formId="saleEditForm"
                            />
                        </div>

                        <!-- سود کل (محاسبه خودکار) -->
                        <div>
                            <x-price-input 
                                name="total_profit"
                                label="سود کل"
                                :value="old('total_profit', $carSale->total_profit)"
                                :readonly="true"
                                inputClass="bg-gray-50"
                                currency="ریال"
                                formId="saleEditForm"
                            />
                        </div>

                        <!-- تاریخ فروش -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ فروش <span class="text-red-500">*</span></label>
                            <input type="text" name="sale_date" id="sale_date" 
                                   value="{{ old('sale_date', $carSale->jalali_sale_date ?? $carSale->sale_date) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition @error('sale_date') border-red-500 @enderror"
                                   placeholder="مثال: 1402/12/25" autocomplete="off" required>
                            @error('sale_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            <p class="text-xs text-gray-500 mt-1">تاریخ را به فرمت شمسی وارد کنید</p>
                        </div>

                        <!-- انتخاب خریدار با کامپوننت searchable-select -->
                        <div class="md:col-span-2">
                            <x-searchable-select 
                                name="person_id"
                                label="انتخاب خریدار"
                                :options="$formattedBuyers"
                                :selected="old('person_id', $carSale->person_id)"
                                placeholder="جستجوی خریدار..."
                                required="true"
                            />
                            <p class="text-xs text-gray-500 mt-1">خریدار را از لیست انتخاب کنید</p>
                            <div class="mt-2 text-sm">
                                <a href="{{ route('people.create') }}" target="_blank" class="text-indigo-600 hover:text-indigo-900">
                                    + ایجاد خریدار جدید
                                </a>
                            </div>
                        </div>

                        <!-- اطلاعات خریدار انتخاب شده -->
                        <div id="buyer_info" class="md:col-span-2 mt-3 p-4 bg-orange-50 rounded-lg hidden">
                            <h4 class="font-bold text-md mb-3 text-orange-800">اطلاعات خریدار انتخاب شده:</h4>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">نام و نام خانوادگی:</span>
                                    <span id="info_full_name" class="font-medium mr-1 block mt-1"></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">کد ملی:</span>
                                    <span id="info_national_code" class="font-medium mr-1 block mt-1"></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">تلفن:</span>
                                    <span id="info_phone" class="font-medium mr-1 block mt-1"></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">نوع شخص:</span>
                                    <span id="info_type" class="font-medium mr-1 block mt-1"></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">ایمیل:</span>
                                    <span id="info_email" class="font-medium mr-1 block mt-1"></span>
                                </div>
                                <div>
                                    <span class="text-gray-600">آدرس:</span>
                                    <span id="info_address" class="font-medium mr-1 block mt-1"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- پیش‌نمایش سود سرمایه‌گذاران -->
                    <div id="profitPreview" class="mt-6 p-4 bg-green-50 rounded-lg hidden">
                        <h3 class="text-lg font-semibold mb-2 text-green-800">پیش‌نمایش سود سرمایه‌گذاران</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-green-200">
                                <thead class="bg-green-100">
                                    <tr>
                                        <th class="px-4 py-2 text-right">سرمایه‌گذار</th>
                                        <th class="px-4 py-2 text-right">درصد مشارکت</th>
                                        <th class="px-4 py-2 text-right">سود</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-green-200" id="investorProfits">
                                    @foreach($carSale->car->investments as $investment)
                                    <tr>
                                        <td class="px-4 py-2">{{ $investment->investor->full_name }}</td>
                                        <td class="px-4 py-2">{{ number_format($investment->percentage, 2) }}%</td>
                                        <td class="px-4 py-2 profit-amount font-bold text-green-700" data-percentage="{{ $investment->percentage }}">0 ریال</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- هشدار تغییر اطلاعات -->
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <div>
                                <p class="text-sm text-yellow-800 font-medium">تغییر اطلاعات فروش</p>
                                <p class="text-xs text-yellow-700 mt-1">
                                    با تغییر قیمت فروش، سود کل و گزارش سود سرمایه‌گذاران به‌طور خودکار به‌روزرسانی خواهد شد.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('car-sales.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105">
                            به‌روزرسانی فروش
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
    $('#sale_date').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true,
        initialValue: true,
        calendar: {
            persian: true
        }
    });

    // محاسبه خودکار سود
    $('#selling_price').on('input', function() {
        const purchasePrice = {{ $carSale->car->purchase_price }};
        
        // گرفتن مقدار از کامپوننت price-input
        const sellingPriceElement = document.querySelector('[name="selling_price"]');
        let sellingPrice = 0;
        
        if (sellingPriceElement) {
            // اگه از price-input استفاده شده
            if (sellingPriceElement._x_dataStack) {
                sellingPrice = sellingPriceElement._x_dataStack[0].getNumericValue?.() || 0;
            } else {
                sellingPrice = parseFloat(sellingPriceElement.value.replace(/[^\d]/g, '')) || 0;
            }
        }
        
        const totalProfit = sellingPrice - purchasePrice;
        
        // آپدیت فیلد سود کل
        const totalProfitElement = document.querySelector('[name="total_profit"]');
        if (totalProfitElement) {
            if (totalProfitElement._x_dataStack) {
                // اگه price-input هست
                totalProfitElement._x_dataStack[0].setValue(totalProfit);
            } else {
                totalProfitElement.value = totalProfit ? Number(totalProfit).toLocaleString('en-US') + ' ریال' : '';
            }
        }
        
        // نمایش یا مخفی کردن بخش پیش‌نمایش
        const profitPreview = $('#profitPreview');
        if (sellingPrice > 0) {
            profitPreview.removeClass('hidden');
            
            // محاسبه سود هر سرمایه‌گذار
            $('.profit-amount').each(function() {
                const percentage = parseFloat($(this).data('percentage')) || 0;
                const investorProfit = (totalProfit * percentage) / 100;
                $(this).text(investorProfit ? Number(investorProfit).toLocaleString('en-US') + ' ریال' : '0 ریال');
            });
        } else {
            profitPreview.addClass('hidden');
        }
    });

    // گوش دادن به تغییرات سلکت باکس
    $(document).on('change', 'select[name="person_id"]', function() {
        var selected = $(this).find(':selected');
        var personId = $(this).val();
        
        if (personId) {
            var fullName = selected.text().split('(')[0].trim();
            var nationalCode = selected.data('national-code');
            var phone = selected.data('phone');
            var type = selected.data('type-label');
            var email = selected.data('email');
            var address = selected.data('address');
            
            $('#info_full_name').text(fullName || '---');
            $('#info_national_code').text(nationalCode || '---');
            $('#info_phone').text(phone || '---');
            $('#info_type').text(type || '---');
            $('#info_email').text(email || '---');
            $('#info_address').text(address || '---');
            
            $('#buyer_info').removeClass('hidden');
        } else {
            $('#buyer_info').addClass('hidden');
        }
    });

    // مقدار اولیه برای اطلاعات خریدار
    @if(old('person_id', $carSale->person_id))
        setTimeout(function() {
            $('select[name="person_id"]').trigger('change');
        }, 500);
    @endif

    // محاسبه اولیه سود
    setTimeout(function() {
        $('#selling_price').trigger('input');
    }, 500);
});
</script>
@endpush