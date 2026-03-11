@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">💰 قیمت‌های لحظه‌ای بازار</h2>
                    <button onclick="updatePrices()" 
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition flex items-center">
                        <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        بروزرسانی قیمت‌ها
                    </button>
                </div>

                <!-- آخرین زمان بروزرسانی -->
                <div class="mb-6 bg-gray-50 p-3 rounded-lg flex justify-between items-center">
                    <div class="text-sm text-gray-600" id="lastUpdate">
                        <span class="font-semibold">آخرین بروزرسانی:</span> {{ now()->format('Y/m/d H:i:s') }}
                    </div>
                    <div class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">
                        منبع: والکس
                    </div>
                </div>

                <!-- کارت‌های خلاصه -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-lg shadow-md">
                        <div class="text-sm opacity-90">دلار (USD)</div>
                        <div class="text-2xl font-bold" id="usd-summary">{{ number_format($prices['currency']['usd'] ?? 0) }}</div>
                        <div class="text-xs opacity-75">تومان</div>
                    </div>
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white p-4 rounded-lg shadow-md">
                        <div class="text-sm opacity-90">طلای 18 عیار</div>
                        <div class="text-2xl font-bold" id="gold-summary">{{ number_format($prices['gold']['geram18'] ?? 0) }}</div>
                        <div class="text-xs opacity-75">تومان</div>
                    </div>
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-4 rounded-lg shadow-md">
                        <div class="text-sm opacity-90">سکه تمام</div>
                        <div class="text-2xl font-bold" id="coin-summary">{{ number_format($prices['coin']['sekeb'] ?? 0) }}</div>
                        <div class="text-xs opacity-75">تومان</div>
                    </div>
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-4 rounded-lg shadow-md">
                        <div class="text-sm opacity-90">بیت‌کوین</div>
                        <div class="text-2xl font-bold" id="btc-summary">{{ number_format($prices['crypto']['btc'] ?? 0) }}</div>
                        <div class="text-xs opacity-75">تومان</div>
                    </div>
                </div>

                <!-- قیمت‌های ارز -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4 text-blue-600 border-r-4 border-blue-600 pr-3">💱 ارزهای خارجی</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @php
                            $currencies = [
                                'usd' => ['name' => 'دلار آمریکا', 'symbol' => 'USD', 'color' => 'blue'],
                                'eur' => ['name' => 'یورو', 'symbol' => 'EUR', 'color' => 'blue'],
                                'gbp' => ['name' => 'پوند انگلیس', 'symbol' => 'GBP', 'color' => 'blue'],
                                'try' => ['name' => 'لیر ترکیه', 'symbol' => 'TRY', 'color' => 'blue'],
                                'aed' => ['name' => 'درهم امارات', 'symbol' => 'AED', 'color' => 'blue'],
                                'cny' => ['name' => 'یوان چین', 'symbol' => 'CNY', 'color' => 'blue'],
                                'jpy' => ['name' => 'ین ژاپن', 'symbol' => 'JPY', 'color' => 'blue'],
                                'chf' => ['name' => 'فرانک سوئیس', 'symbol' => 'CHF', 'color' => 'blue'],
                            ];
                        @endphp
                        
                        @foreach($currencies as $key => $currency)
                            @if(isset($prices['currency'][$key]))
                            <div class="bg-{{ $currency['color'] }}-50 p-4 rounded-lg hover:shadow-md transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="text-sm text-gray-600">{{ $currency['name'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $currency['symbol'] }}</div>
                                    </div>
                                    <span class="text-xs bg-{{ $currency['color'] }}-200 text-{{ $currency['color'] }}-800 px-2 py-1 rounded-full">ریال</span>
                                </div>
                                <div class="text-2xl font-bold text-{{ $currency['color'] }}-700 mt-2" id="{{ $key }}">
                                    {{ number_format($prices['currency'][$key]) }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">قیمت به ریال</div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- قیمت‌های طلا و سکه -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- طلا -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4 text-yellow-600 border-r-4 border-yellow-600 pr-3">🥇 طلا</h3>
                        <div class="grid grid-cols-1 gap-4">
                            @php
                                $golds = [
                                    'geram18' => 'طلای 18 عیار',
                                    'geram24' => 'طلای 24 عیار',
                                    'mithqal' => 'مثقال طلا',
                                ];
                            @endphp
                            
                            @foreach($golds as $key => $label)
                                @if(isset($prices['gold'][$key]))
                                <div class="bg-yellow-50 p-4 rounded-lg hover:shadow-md transition">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">{{ $label }}</span>
                                        <span class="text-xs bg-yellow-200 text-yellow-800 px-2 py-1 rounded-full">هر گرم</span>
                                    </div>
                                    <div class="text-2xl font-bold text-yellow-700 mt-1" id="{{ $key }}">
                                        {{ number_format($prices['gold'][$key]) }}
                                    </div>
                                    <div class="text-xs text-gray-500">ریال</div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- سکه -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4 text-orange-600 border-r-4 border-orange-600 pr-3">🪙 سکه</h3>
                        <div class="grid grid-cols-1 gap-4">
                            @php
                                $coins = [
                                    'sekeb' => 'سکه تمام بهار آزادی',
                                    'nim' => 'نیم سکه',
                                    'rob' => 'ربع سکه',
                                    'gerami' => 'سکه گرمی',
                                ];
                            @endphp
                            
                            @foreach($coins as $key => $label)
                                @if(isset($prices['coin'][$key]))
                                <div class="bg-orange-50 p-4 rounded-lg hover:shadow-md transition">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-700">{{ $label }}</span>
                                        <span class="text-xs bg-orange-200 text-orange-800 px-2 py-1 rounded-full">سکه</span>
                                    </div>
                                    <div class="text-2xl font-bold text-orange-700 mt-1" id="{{ $key }}">
                                        {{ number_format($prices['coin'][$key]) }}
                                    </div>
                                    <div class="text-xs text-gray-500">ریال</div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- قیمت‌های ارز دیجیتال -->
                @if(isset($prices['crypto']) && !empty(array_filter($prices['crypto'])))
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4 text-purple-600 border-r-4 border-purple-600 pr-3">₿ ارزهای دیجیتال</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        @php
                            $cryptos = [
                                'btc' => ['name' => 'بیت‌کوین', 'symbol' => 'BTC', 'icon' => '₿'],
                                'eth' => ['name' => 'اتریوم', 'symbol' => 'ETH', 'icon' => 'Ξ'],
                                'usdt' => ['name' => 'تتر', 'symbol' => 'USDT', 'icon' => '₮'],
                                'bnb' => ['name' => 'بایننس کوین', 'symbol' => 'BNB', 'icon' => 'BNB'],
                                'xrp' => ['name' => 'ریپل', 'symbol' => 'XRP', 'icon' => 'XRP'],
                            ];
                        @endphp
                        
                        @foreach($cryptos as $key => $crypto)
                            @if(isset($prices['crypto'][$key]) && $prices['crypto'][$key] > 0)
                            <div class="bg-purple-50 p-4 rounded-lg hover:shadow-md transition">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="text-sm text-gray-600">{{ $crypto['name'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $crypto['symbol'] }}</div>
                                    </div>
                                    <span class="text-lg">{{ $crypto['icon'] }}</span>
                                </div>
                                <div class="text-xl font-bold text-purple-700 mt-2" id="{{ $key }}">
                                    {{ number_format($prices['crypto'][$key]) }}
                                </div>
                                <div class="text-xs text-gray-500">تومان</div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- وضعیت بازار و اطلاعات تکمیلی -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-gray-500 border-t pt-4">
                    <div>
                        <span class="font-semibold">🔄 آخرین بروزرسانی:</span> 
                        <span id="detailed-update">{{ $prices['currency']['updated_at'] ?? now() }}</span>
                    </div>
                    <div>
                        <span class="font-semibold">📊 منبع داده‌ها:</span> 
                        <span class="text-green-600">صرافی والکس (Wallex) + محاسبات داخلی</span>
                    </div>
                    <div>
                        <span class="font-semibold">💡 قیمت‌های ارز:</span> بر اساس تتر (USDT) محاسبه شده
                    </div>
                    <div>
                        <span class="font-semibold">⏱ بروزرسانی خودکار:</span> هر ۵ دقیقه یکبار
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let autoUpdateInterval;

    function updatePrices() {
        const button = event.currentTarget;
        const originalText = button.innerHTML;
        
        button.disabled = true;
        button.innerHTML = `
            <svg class="animate-spin h-5 w-5 inline ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            در حال بروزرسانی...
        `;

        fetch('{{ route("prices.update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
        })
        .then(response => {
            if (!response.ok) throw new Error('خطا در بروزرسانی');
            return response.json();
        })
        .then(data => {
            if (data.success) {
                return fetch('{{ route("prices.json") }}')
                    .then(res => res.json())
                    .then(prices => {
                        updateAllPrices(prices);
                        showNotification('✅ قیمت‌ها با موفقیت بروزرسانی شدند', 'success');
                    });
            } else {
                throw new Error(data.message || 'خطا در بروزرسانی');
            }
        })
        .catch(error => {
            showNotification('❌ ' + error.message, 'error');
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalText;
        });
    }

    function updateAllPrices(prices) {
        // آپدیت همه فیلدها
        const updates = [
            // ارزها
            {id: 'usd', path: 'currency.usd'},
            {id: 'eur', path: 'currency.eur'},
            {id: 'gbp', path: 'currency.gbp'},
            {id: 'try', path: 'currency.try'},
            {id: 'aed', path: 'currency.aed'},
            {id: 'cny', path: 'currency.cny'},
            {id: 'jpy', path: 'currency.jpy'},
            {id: 'chf', path: 'currency.chf'},
            
            // طلا
            {id: 'geram18', path: 'gold.geram18'},
            {id: 'geram24', path: 'gold.geram24'},
            {id: 'mithqal', path: 'gold.mithqal'},
            
            // سکه
            {id: 'sekeb', path: 'coin.sekeb'},
            {id: 'nim', path: 'coin.nim'},
            {id: 'rob', path: 'coin.rob'},
            {id: 'gerami', path: 'coin.gerami'},
            
            // ارز دیجیتال
            {id: 'btc', path: 'crypto.btc'},
            {id: 'eth', path: 'crypto.eth'},
            {id: 'usdt', path: 'crypto.usdt'},
            {id: 'bnb', path: 'crypto.bnb'},
            {id: 'xrp', path: 'crypto.xrp'},
            
            // خلاصه‌ها
            {id: 'usd-summary', path: 'currency.usd'},
            {id: 'gold-summary', path: 'gold.geram18'},
            {id: 'coin-summary', path: 'coin.sekeb'},
            {id: 'btc-summary', path: 'crypto.btc'},
        ];

        updates.forEach(update => {
            const element = document.getElementById(update.id);
            if (element) {
                const value = getNestedValue(prices, update.path);
                element.textContent = formatNumber(value);
            }
        });

        // آپدیت زمان
        const now = new Date();
        const timeStr = now.toLocaleDateString('fa-IR') + ' ' + now.toLocaleTimeString('fa-IR');
        document.getElementById('lastUpdate').innerHTML = `<span class="font-semibold">آخرین بروزرسانی:</span> ${timeStr}`;
        document.getElementById('detailed-update').textContent = timeStr;
    }

    function getNestedValue(obj, path) {
        return path.split('.').reduce((current, key) => current && current[key], obj) || 0;
    }

    function formatNumber(number) {
        if (!number && number !== 0) return '۰';
        return new Intl.NumberFormat('fa-IR').format(number);
    }

    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 left-4 p-4 rounded-lg shadow-lg text-white ${
            type === 'success' ? 'bg-green-500' : 'bg-red-500'
        } transition-all duration-300 z-50 transform translate-y-0 opacity-100`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // شروع بروزرسانی خودکار
    function startAutoUpdate() {
        if (autoUpdateInterval) clearInterval(autoUpdateInterval);
        
        autoUpdateInterval = setInterval(() => {
            fetch('{{ route("prices.json") }}')
                .then(response => response.json())
                .then(data => {
                    updateAllPrices(data);
                })
                .catch(error => console.error('Auto update error:', error));
        }, 300000); // 5 دقیقه
    }

    // توقف بروزرسانی خودکار
    function stopAutoUpdate() {
        if (autoUpdateInterval) {
            clearInterval(autoUpdateInterval);
            autoUpdateInterval = null;
        }
    }

    // شروع وقتی صفحه لود شد
    document.addEventListener('DOMContentLoaded', startAutoUpdate);

    // توقف وقتی صفحه بسته میشه
    window.addEventListener('beforeunload', stopAutoUpdate);

    // آپدیت اولیه بعد از لود صفحه
    setTimeout(() => {
        fetch('{{ route("prices.json") }}')
            .then(response => response.json())
            .then(data => updateAllPrices(data))
            .catch(error => console.error('Initial update error:', error));
    }, 1000);
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.grid > div {
    animation: fadeIn 0.5s ease-out;
}

.bg-gradient-to-br {
    transition: transform 0.3s ease;
}

.bg-gradient-to-br:hover {
    transform: translateY(-5px);
}
</style>
@endpush