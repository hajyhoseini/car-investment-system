@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6">ثبت فروش خودرو: {{ $car->title }}</h2>

                <!-- خلاصه اطلاعات خودرو -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <span class="text-sm text-gray-600">قیمت خرید:</span>
                        <span class="text-lg font-bold text-blue-600 mr-2">{{ number_format($car->purchase_price) }} ریال</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">کل سرمایه‌گذاری:</span>
                        <span class="text-lg font-bold text-green-600 mr-2">{{ number_format($car->total_invested) }} ریال</span>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">تعداد سرمایه‌گذاران:</span>
                        <span class="text-lg font-bold text-purple-600 mr-2">{{ $car->investments->count() }} نفر</span>
                    </div>
                </div>

                <!-- لیست سرمایه‌گذاران این خودرو -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-2">سرمایه‌گذاران این خودرو</h3>
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
                                <td class="px-4 py-2">{{ $investment->percentage }}%</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <form method="POST" action="{{ route('cars.sell.store', $car) }}" id="saleForm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- قیمت فروش -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">قیمت فروش (ریال) <span class="text-red-500">*</span></label>
                            <input type="number" name="selling_price" id="selling_price" value="{{ old('selling_price') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            @error('selling_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- سود کل (محاسبه خودکار) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">سود کل</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="text" id="total_profit" name="total_profit" class="block w-full rounded-md border-gray-300 bg-gray-50" readonly placeholder="خودکار محاسبه می‌شود">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">ریال</span>
                                </div>
                            </div>
                        </div>

                        <!-- تاریخ فروش -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">تاریخ فروش <span class="text-red-500">*</span></label>
                            <input type="date" name="sale_date" value="{{ old('sale_date', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            @error('sale_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- نام خریدار -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">نام خریدار <span class="text-red-500">*</span></label>
                            <input type="text" name="buyer_name" value="{{ old('buyer_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            @error('buyer_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- تلفن خریدار -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">تلفن خریدار <span class="text-red-500">*</span></label>
                            <input type="text" name="buyer_phone" value="{{ old('buyer_phone') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            @error('buyer_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- پیش‌نمایش سود سرمایه‌گذاران -->
                    <div id="profitPreview" class="mt-6 p-4 bg-green-50 rounded-lg hidden">
                        <h3 class="text-lg font-semibold mb-2">پیش‌نمایش سود سرمایه‌گذاران</h3>
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
                                        <td class="px-4 py-2">{{ $investment->percentage }}%</td>
                                        <td class="px-4 py-2 profit-amount" data-percentage="{{ $investment->percentage }}">0 ریال</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('cars.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
                            انصراف
                        </a>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
                            ثبت فروش
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('selling_price').addEventListener('input', function() {
    const purchasePrice = {{ $car->purchase_price }};
    const sellingPrice = parseFloat(this.value) || 0;
    const totalProfit = sellingPrice - purchasePrice;
    
    // نمایش سود کل
    document.getElementById('total_profit').value = totalProfit.toLocaleString();
    
    // نمایش یا مخفی کردن بخش پیش‌نمایش
    const profitPreview = document.getElementById('profitPreview');
    if (sellingPrice > 0) {
        profitPreview.classList.remove('hidden');
        
        // محاسبه سود هر سرمایه‌گذار
        document.querySelectorAll('.profit-amount').forEach(function(element) {
            const percentage = parseFloat(element.dataset.percentage) || 0;
            const investorProfit = (totalProfit * percentage) / 100;
            element.textContent = investorProfit.toLocaleString() + ' ریال';
        });
    } else {
        profitPreview.classList.add('hidden');
    }
});
</script>
@endpush
@endsection