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
                    <h2 class="text-2xl font-bold">ثبت فروش خودرو: {{ $car->title }}</h2>
                    <a href="{{ route('cars.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        بازگشت به لیست
                    </a>
                </div>

                <!-- خلاصه اطلاعات خودرو -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <span class="text-sm text-gray-600">قیمت خرید:</span>
                        <span class="text-lg font-bold text-blue-600 mr-2">{{ number_format($car->purchase_price) }} ریال</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">کل سرمایه‌گذاری:</span>
                        <span class="text-lg font-bold text-green-600 mr-2">{{ number_format($car->investments->sum('amount')) }} ریال</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">تعداد سرمایه‌گذاران:</span>
                        <span class="text-lg font-bold text-purple-600 mr-2">{{ $car->investments->count() }} نفر</span>
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
                                @foreach($car->investments as $investment)
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

                <form method="POST" action="{{ route('cars.sell.store', $car) }}" id="saleForm" onsubmit="return prepareForm()">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- قیمت فروش -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">قیمت فروش (ریال) <span class="text-red-500">*</span></label>
                            <input type="text" name="selling_price" id="selling_price" value="{{ old('selling_price') }}" 
                                   class="price-format w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition @error('selling_price') border-red-500 @enderror" 
                                   placeholder="مثال: ۱۵,۰۰۰,۰۰۰,۰۰۰" required>
                            @error('selling_price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- سود کل (محاسبه خودکار) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">سود کل</label>
                            <div class="relative">
                                <input type="text" id="total_profit" name="total_profit" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50" 
                                       readonly placeholder="خودکار محاسبه می‌شود">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">ریال</span>
                                </div>
                            </div>
                        </div>

                        <!-- تاریخ فروش -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">تاریخ فروش <span class="text-red-500">*</span></label>
                            <input type="date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition @error('sale_date') border-red-500 @enderror" required>
                            @error('sale_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- انتخاب خریدار با کامپوننت searchable-select -->
                        <div class="md:col-span-2">
                            <x-searchable-select 
                                name="person_id"
                                label="انتخاب خریدار"
                                :options="$formattedBuyers"
                                :selected="old('person_id')"
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
                                    @foreach($car->investments as $investment)
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

                    <div class="flex justify-end mt-6 space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('cars.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                            انصراف
                        </a>
                        <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition transform hover:scale-105">
                            ثبت فروش
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// تابع آماده‌سازی فرم برای ارسال
function prepareForm() {
    // پاک کردن ویرگول از قیمت فروش
    const sellingPrice = document.getElementById('selling_price');
    if (sellingPrice) {
        const cleanValue = sellingPrice.value.replace(/,/g, '');
        sellingPrice.value = cleanValue;
    }
    
    // پاک کردن ویرگول از سود کل
    const totalProfit = document.getElementById('total_profit');
    if (totalProfit) {
        const cleanValue = totalProfit.value.replace(/,/g, '').replace(' ریال', '');
        totalProfit.value = cleanValue;
    }
    
    // بررسی اینکه خریدار انتخاب شده باشد
    const personId = document.querySelector('input[name="person_id"]')?.value;
    if (!personId) {
        alert('لطفاً خریدار را انتخاب کنید');
        return false;
    }
    
    return true; // اجازه ارسال فرم داده بشه
}

$(document).ready(function() {
    // فرمت عدد با ویرگول
    $('.price-format').on('input', function() {
        let value = this.value.replace(/[^\d]/g, '');
        if (value) {
            this.value = Number(value).toLocaleString('en-US');
        } else {
            this.value = '';
        }
    });

    // محاسبه سود
    $('#selling_price').on('input', function() {
        const purchasePrice = {{ $car->purchase_price }};
        const sellingPrice = parseFloat(this.value.replace(/[^\d]/g, '')) || 0;
        const totalProfit = sellingPrice - purchasePrice;
        
        // نمایش سود کل
        $('#total_profit').val(totalProfit ? Number(totalProfit).toLocaleString('en-US') + ' ریال' : '');
        
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

    // گوش دادن به تغییرات سلکت باکس (برای کامپوننت searchable-select)
    $(document).on('change', 'select[name="person_id"]', function() {
        var selected = $(this).find(':selected');
        var personId = $(this).val();
        
        if (personId) {
            // اطلاعات از data-attributes سلکت شده
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

    // اگر مقدار old وجود داشت، اطلاعات رو نشون بده
    @if(old('person_id'))
        setTimeout(function() {
            $('select[name="person_id"]').trigger('change');
        }, 500);
    @endif
});
</script>
@endpush