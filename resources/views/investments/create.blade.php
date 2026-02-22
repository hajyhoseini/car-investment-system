@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6">ثبت سرمایه‌گذاری جدید</h2>

                <form method="POST" action="{{ route('investments.store') }}" id="investmentForm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- انتخاب خودرو -->
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">خودرو <span class="text-red-500">*</span></label>
    <select name="car_id" id="car_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
        <option value="">انتخاب کنید</option>
        @foreach($cars as $car)
            @php
                $remaining = $car->purchase_price - $car->total_invested;
                $fundedPercentage = ($car->total_invested / $car->purchase_price) * 100;
            @endphp
            <option value="{{ $car->id }}" 
                data-price="{{ $car->purchase_price }}"
                data-remaining="{{ $remaining }}"
                {{ old('car_id') == $car->id ? 'selected' : '' }}>
                {{ $car->title }} - {{ $car->brand }} {{ $car->model }} 
                ({{ number_format($car->purchase_price) }} ریال) 
                - تأمین شده: {{ number_format($fundedPercentage, 1) }}% 
                - باقی‌مانده: {{ number_format($remaining) }} ریال
            </option>
        @endforeach
    </select>
    @error('car_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
</div>

                        <!-- انتخاب سرمایه‌گذار -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">سرمایه‌گذار <span class="text-red-500">*</span></label>
                            <select name="investor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">انتخاب کنید</option>
                                  @foreach($investors as $investor)
        <option value="{{ $investor->id }}" 
            {{ auth()->user()->investor && auth()->user()->investor->id == $investor->id ? 'selected' : '' }}>
            {{ $investor->full_name }}
            @if($investor->user_id == auth()->id())
                (شما)
            @endif
        </option>
    @endforeach
                            </select>
                            @error('investor_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- مبلغ سرمایه‌گذاری -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">مبلغ سرمایه‌گذاری (ریال) <span class="text-red-500">*</span></label>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- درصد مشارکت (محاسبه خودکار) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">درصد مشارکت</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="text" id="percentage" name="percentage" value="{{ old('percentage') }}" class="block w-full rounded-md border-gray-300 bg-gray-50 pr-12" readonly placeholder="خودکار محاسبه می‌شود">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">%</span>
                                </div>
                            </div>
                            @error('percentage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- تاریخ سرمایه‌گذاری -->
                    
<div>
    <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ سرمایه‌گذاری <span class="text-red-500">*</span></label>
    <input type="text" name="investment_date" id="investment_date" value="{{ old('investment_date', $todayJalali ?? '') }}" 
           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition @error('investment_date') border-red-500 @enderror"
           placeholder="مثال: 1402/12/25" autocomplete="off">
    @error('investment_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    <p class="text-xs text-gray-500 mt-1">تاریخ را به فرمت شمسی وارد کنید (مثال: 1402/12/25)</p>
</div>
</div>


                    <!-- نمایش خلاصه سرمایه‌گذاری‌های قبلی این خودرو -->
                    <div id="investmentSummary" class="mt-6 p-4 bg-blue-50 rounded-lg hidden">
                        <h3 class="text-lg font-semibold mb-2">خلاصه سرمایه‌گذاری‌های این خودرو</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm text-gray-600">قیمت خودرو:</span>
                                <span class="text-lg font-bold text-blue-600 mr-2" id="carPrice">0</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">مجموع سرمایه‌گذاری شده:</span>
                                <span class="text-lg font-bold text-green-600 mr-2" id="totalInvested">0</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">مبلغ باقی‌مانده:</span>
                                <span class="text-lg font-bold text-orange-600 mr-2" id="remainingAmount">0</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">درصد تأمین شده:</span>
                                <span class="text-lg font-bold text-purple-600 mr-2" id="fundedPercentage">0%</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('investments.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
                            انصراف
                        </a>
                        <button type="submit" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition">
                            ثبت سرمایه‌گذاری
                        </button>
                    </div>
                </form>
        </div>
    </div>
</div>

@push('scripts')

<script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">

<script>
    $(document).ready(function() {
        $('#investment_date').persianDatepicker({
            format: 'YYYY/MM/DD',
            autoClose: true,
            initialValue: true,
            calendar: {
                persian: true
            }
        });
    });
</script>
<script>
document.getElementById('car_id').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const price = selected.dataset.price;
    
    if (price) {
        // اینجا می‌تونید با AJAX اطلاعات سرمایه‌گذاری‌های قبلی رو بگیرید
        document.getElementById('carPrice').textContent = Number(price).toLocaleString() + ' ریال';
        document.getElementById('investmentSummary').classList.remove('hidden');
    } else {
        document.getElementById('investmentSummary').classList.add('hidden');
    }
});

document.getElementById('amount').addEventListener('input', function() {
    const selected = document.getElementById('car_id').options[document.getElementById('car_id').selectedIndex];
    const price = selected.dataset.price;
    const amount = this.value;
    
    if (price && amount) {
        const percentage = (amount / price * 100).toFixed(2);
        document.getElementById('percentage').value = percentage;
    } else {
        document.getElementById('percentage').value = '';
    }
});
</script>
@endpush
@endsection