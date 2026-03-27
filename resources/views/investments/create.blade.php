@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6">ثبت سرمایه‌گذاری جدید</h2>

                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('investments.store') }}" id="investmentForm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- انتخاب خودرو با قابلیت جستجو -->
                        <div x-data="searchableSelect({
                            name: 'car_id',
                            id: 'car_id',
                            options: {{ json_encode(
                                $cars->map(function($car) {
                                    $remaining = $car->purchase_price - $car->total_invested;
                                    $fundedPercentage = ($car->total_invested / $car->purchase_price) * 100;
                                    return [
                                        'id' => $car->id,
                                        'text' => $car->title . ' - ' . $car->brand . ' ' . $car->model,
                                        'subtext' => number_format($car->purchase_price) . ' ریال - ' . 
                                                     number_format($fundedPercentage, 1) . '% تأمین - ' .
                                                     number_format($remaining) . ' ریال باقی‌مانده',
                                        'data' => [
                                            'price' => $car->purchase_price,
                                            'remaining' => $remaining,
                                            'total_invested' => $car->total_invested
                                        ]
                                    ];
                                })->values()
                            ) }},
                            selected: '{{ old('car_id') }}',
                            placeholder: 'جستجوی خودرو...'
                        })" class="relative searchable-select-wrapper">
                            <label for="car_id" class="block text-sm font-medium text-gray-700 mb-2">
                                خودرو
                                <span class="text-red-500">*</span>
                            </label>

                            <!-- فیلد مخفی برای ارسال مقدار به سرور -->
                            <input type="hidden" name="car_id" id="car_id" x-model="selectedValue" value="{{ old('car_id') }}">

                            <!-- باکس انتخابی سفارشی -->
                            <div class="relative">
                                <div 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white cursor-pointer flex justify-between items-center transition hover:border-purple-500 focus-within:ring-2 focus-within:ring-purple-500 focus-within:border-transparent"
                                    :class="{ 'ring-2 ring-purple-500 border-transparent': isOpen }"
                                    @click="toggleDropdown()"
                                >
                                    <span x-text="selectedText" :class="{ 'text-gray-400': !selectedValue }" class="truncate"></span>
                                    <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'transform rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>

                                <!-- دراپ دان جستجو -->
                                <div 
                                    x-show="isOpen" 
                                    @click.away="closeDropdown()"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-xl shadow-lg overflow-hidden"
                                    style="max-height: 350px; display: flex; flex-direction: column;"
                                >
                                    <!-- باکس جستجو -->
                                    <div class="sticky top-0 bg-white p-2 border-b z-10">
                                        <div class="relative">
                                            <input type="text" 
                                                   x-model="searchText"
                                                   class="w-full px-3 py-2 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                                   :placeholder="placeholder"
                                                   @click.stop
                                                   @keydown.escape="closeDropdown()"
                                                   @keydown.enter.prevent="selectFirstOption()"
                                                   @keydown.down.prevent="moveSelection(1)"
                                                   @keydown.up.prevent="moveSelection(-1)">
                                            <svg class="absolute left-2 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- لیست گزینه‌ها -->
                                    <div class="overflow-y-auto" style="max-height: 250px;" x-ref="optionsContainer">
                                        <template x-for="(option, index) in filteredOptions" :key="option.id">
                                            <div 
                                                class="px-4 py-2 cursor-pointer transition"
                                                :class="{
                                                    'bg-purple-50': selectedValue == option.id,
                                                    'hover:bg-purple-50': selectedValue != option.id,
                                                    'bg-purple-100': hoveredIndex === index
                                                }"
                                                @click="selectOption(option)"
                                                @mouseenter="hoveredIndex = index"
                                                x-ref="optionItems"
                                            >
                                                <div class="font-medium" x-text="option.text"></div>
                                                <div class="text-sm text-gray-500" x-text="option.subtext" x-show="option.subtext"></div>
                                            </div>
                                        </template>
                                        <div x-show="filteredOptions.length === 0" class="px-4 py-8 text-gray-500 text-center">
                                            <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            <p>موردی یافت نشد</p>
                                        </div>
                                    </div>
                                    
                                    <!-- نمایش تعداد نتایج -->
                                    <div class="sticky bottom-0 bg-gray-50 px-4 py-1 text-xs text-gray-500 border-t" x-show="filteredOptions.length > 0">
                                        <span x-text="filteredOptions.length"></span> مورد یافت شد
                                    </div>
                                </div>
                            </div>
                            @error('car_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- انتخاب سرمایه‌گذار با قابلیت جستجو -->
                        <div x-data="searchableSelect({
                            name: 'investor_id',
                            id: 'investor_id',
                            options: {{ json_encode(
                                $investors->map(function($investor) {
                                    return [
                                        'id' => $investor->id,
                                        'text' => $investor->full_name,
                                        'subtext' => $investor->user_id == auth()->id() ? 'شما' : '',
                                        'data' => []
                                    ];
                                })->values()
                            ) }},
                            selected: '{{ auth()->user()->investor ? auth()->user()->investor->id : old('investor_id') }}',
                            placeholder: 'جستجوی سرمایه‌گذار...'
                        })" class="relative searchable-select-wrapper">
                            <label for="investor_id" class="block text-sm font-medium text-gray-700 mb-2">
                                سرمایه‌گذار
                                <span class="text-red-500">*</span>
                            </label>

                            <!-- فیلد مخفی برای ارسال مقدار به سرور -->
                            <input type="hidden" name="investor_id" id="investor_id" x-model="selectedValue" value="{{ auth()->user()->investor ? auth()->user()->investor->id : old('investor_id') }}">

                            <!-- باکس انتخابی سفارشی -->
                            <div class="relative">
                                <div 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-white cursor-pointer flex justify-between items-center transition hover:border-purple-500 focus-within:ring-2 focus-within:ring-purple-500 focus-within:border-transparent"
                                    :class="{ 'ring-2 ring-purple-500 border-transparent': isOpen }"
                                    @click="toggleDropdown()"
                                >
                                    <span x-text="selectedText" :class="{ 'text-gray-400': !selectedValue }" class="truncate"></span>
                                    <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'transform rotate-180': isOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>

                                <!-- دراپ دان جستجو (همان ساختار بالا) -->
                                <div 
                                    x-show="isOpen" 
                                    @click.away="closeDropdown()"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-xl shadow-lg overflow-hidden"
                                    style="max-height: 350px; display: flex; flex-direction: column;"
                                >
                                    <!-- باکس جستجو -->
                                    <div class="sticky top-0 bg-white p-2 border-b z-10">
                                        <div class="relative">
                                            <input type="text" 
                                                   x-model="searchText"
                                                   class="w-full px-3 py-2 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                                   :placeholder="placeholder"
                                                   @click.stop
                                                   @keydown.escape="closeDropdown()"
                                                   @keydown.enter.prevent="selectFirstOption()"
                                                   @keydown.down.prevent="moveSelection(1)"
                                                   @keydown.up.prevent="moveSelection(-1)">
                                            <svg class="absolute left-2 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- لیست گزینه‌ها -->
                                    <div class="overflow-y-auto" style="max-height: 250px;" x-ref="optionsContainer">
                                        <template x-for="(option, index) in filteredOptions" :key="option.id">
                                            <div 
                                                class="px-4 py-2 cursor-pointer transition"
                                                :class="{
                                                    'bg-purple-50': selectedValue == option.id,
                                                    'hover:bg-purple-50': selectedValue != option.id,
                                                    'bg-purple-100': hoveredIndex === index
                                                }"
                                                @click="selectOption(option)"
                                                @mouseenter="hoveredIndex = index"
                                                x-ref="optionItems"
                                            >
                                                <div class="font-medium" x-text="option.text"></div>
                                                <div class="text-sm text-gray-500" x-text="option.subtext" x-show="option.subtext"></div>
                                            </div>
                                        </template>
                                        <div x-show="filteredOptions.length === 0" class="px-4 py-8 text-gray-500 text-center">
                                            <svg class="mx-auto h-8 w-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            <p>موردی یافت نشد</p>
                                        </div>
                                    </div>
                                    
                                    <!-- نمایش تعداد نتایج -->
                                    <div class="sticky bottom-0 bg-gray-50 px-4 py-1 text-xs text-gray-500 border-t" x-show="filteredOptions.length > 0">
                                        <span x-text="filteredOptions.length"></span> مورد یافت شد
                                    </div>
                                </div>
                            </div>
                            @error('investor_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <!-- مبلغ سرمایه‌گذاری با کامپوننت -->
                        <x-price-input 
                            name="amount" 
                            label="مبلغ سرمایه‌گذاری (ریال)" 
                            :value="old('amount')"
                            :min="1000"
                            required
                            class="text-left"
                        />

                        <!-- تاریخ سرمایه‌گذاری -->
                        <div>
                            <label for="investment_date" class="block text-sm font-medium text-gray-700 mb-2">تاریخ سرمایه‌گذاری <span class="text-red-500">*</span></label>
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
                        <button type="submit" id="submitBtn" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition">
                            ثبت سرمایه‌گذاری
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')

<script>
    // تابع searchableSelect (بدون تغییر)
    function searchableSelect(config) {
        return {
            name: config.name,
            id: config.id,
            options: config.options,
            selectedValue: config.selected,
            selectedText: config.placeholder || 'انتخاب کنید',
            searchText: '',
            isOpen: false,
            hoveredIndex: -1,
            placeholder: config.placeholder,

            init() {
                if (this.selectedValue && this.selectedValue !== '') {
                    const selected = this.options.find(opt => opt.id == this.selectedValue);
                    if (selected) {
                        this.selectedText = selected.subtext ? 
                            `${selected.text} - ${selected.subtext}` : 
                            selected.text;
                        
                        if (selected.data && this.name === 'car_id') {
                            this.updateCarData(selected.data);
                        }
                    }
                }

                this.$watch('searchText', () => {
                    this.hoveredIndex = -1;
                });
                
                this.$watch('hoveredIndex', (index) => {
                    if (index >= 0 && this.$refs.optionsContainer) {
                        this.$nextTick(() => {
                            const items = this.$refs.optionsContainer.children;
                            if (items[index]) {
                                items[index].scrollIntoView({ 
                                    behavior: 'smooth', 
                                    block: 'nearest' 
                                });
                            }
                        });
                    }
                });
            },

            get filteredOptions() {
                if (!this.searchText || this.searchText.trim() === '') {
                    return this.options;
                }
                
                const search = this.searchText.trim().toLowerCase();
                return this.options.filter(option => {
                    const text = (option.text || '').toLowerCase();
                    const subtext = (option.subtext || '').toLowerCase();
                    return text.includes(search) || subtext.includes(search);
                });
            },

            toggleDropdown() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    this.searchText = '';
                    this.hoveredIndex = -1;
                    this.$nextTick(() => {
                        const input = this.$el.querySelector('input[type="text"]');
                        if (input) input.focus();
                    });
                }
            },

            closeDropdown() {
                this.isOpen = false;
                this.searchText = '';
                this.hoveredIndex = -1;
            },

            selectOption(option) {
                this.selectedValue = option.id;
                this.selectedText = option.subtext ? 
                    `${option.text} - ${option.subtext}` : 
                    option.text;
                
                if (option.data && this.name === 'car_id') {
                    this.updateCarData(option.data);
                }
                
                this.closeDropdown();
                
                const event = new CustomEvent('change', { 
                    detail: { value: option.id, option: option }
                });
                this.$el.dispatchEvent(event);
                
                const inputEvent = new Event('input', { bubbles: true });
                const hiddenInput = this.$el.querySelector(`input[type="hidden"][name="${this.name}"]`);
                if (hiddenInput) {
                    hiddenInput.dispatchEvent(inputEvent);
                }
            },

            selectFirstOption() {
                if (this.filteredOptions.length > 0) {
                    this.selectOption(this.filteredOptions[0]);
                }
            },

            moveSelection(direction) {
                const options = this.filteredOptions;
                if (options.length === 0) return;

                let newIndex = this.hoveredIndex + direction;
                if (newIndex < 0) newIndex = 0;
                if (newIndex >= options.length) newIndex = options.length - 1;
                
                this.hoveredIndex = newIndex;
            },

            updateCarData(data) {
                const summary = document.getElementById('investmentSummary');
                if (summary) {
                    document.getElementById('carPrice').textContent = Number(data.price).toLocaleString('fa-IR') + ' ریال';
                    document.getElementById('totalInvested').textContent = Number(data.total_invested).toLocaleString('fa-IR') + ' ریال';
                    document.getElementById('remainingAmount').textContent = Number(data.remaining).toLocaleString('fa-IR') + ' ریال';
                    
                    const fundedPercentage = ((data.total_invested / data.price) * 100).toFixed(1);
                    document.getElementById('fundedPercentage').textContent = fundedPercentage + '%';
                    
                    summary.classList.remove('hidden');
                }
            }
        }
    }

    // راه‌اندازی persian datepicker
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

    // event listenerها
    document.addEventListener('DOMContentLoaded', function() {
        // قبل از ارسال فرم
        document.getElementById('investmentForm')?.addEventListener('submit', function(e) {
            const amount = document.getElementById('amount').value;
            const carId = document.getElementById('car_id').value;
            const investorId = document.getElementById('investor_id').value;
            
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
    });
</script>
@endpush
@endsection