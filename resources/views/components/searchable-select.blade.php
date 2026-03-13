@props([
    'name' => 'person_id',
    'id' => null,
    'label' => 'انتخاب کنید',
    'options' => [],
    'selected' => null,
    'placeholder' => 'جستجو...',
    'required' => false,
    'error' => null
])

@php
    $id = $id ?? $name;
    $selectedValue = old($name, $selected);
@endphp

<div {{ $attributes->merge(['class' => 'relative']) }} x-data="searchableSelect({
    name: '{{ $name }}',
    id: '{{ $id }}',
    options: {{ json_encode($options) }},
    selected: '{{ $selectedValue }}',
    placeholder: '{{ $placeholder }}'
})">
    @if($label)
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    @endif

    <!-- این فیلد مخفی برای ارسال مقدار به سرور -->
    <input type="hidden" name="{{ $name }}" id="{{ $id }}" x-model="selectedValue">

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
            class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-xl shadow-lg"
            style="max-height: 300px; overflow-y: auto;"
        >
            <!-- باکس جستجو -->
            <div class="sticky top-0 bg-white p-2 border-b">
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
            <div class="py-1">
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
                    >
                        <div class="font-medium" x-text="option.text"></div>
                        <div class="text-sm text-gray-500" x-text="option.subtext" x-show="option.subtext"></div>
                    </div>
                </template>
                <div x-show="filteredOptions.length === 0" class="px-4 py-3 text-gray-500 text-center">
                    موردی یافت نشد
                </div>
            </div>
        </div>
    </div>

    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>

@push('scripts')
<script>
function searchableSelect(config) {
    return {
        name: config.name,
        id: config.id,
        options: config.options,
        selectedValue: config.selected,
        selectedText: 'انتخاب کنید',
        searchText: '',
        isOpen: false,
        hoveredIndex: -1,
        placeholder: config.placeholder,

        init() {
            // تنظیم متن انتخاب شده اولیه
            if (this.selectedValue) {
                const selected = this.options.find(opt => opt.id == this.selectedValue);
                if (selected) {
                    this.selectedText = selected.subtext ? 
                        `${selected.text} - ${selected.subtext}` : 
                        selected.text;
                }
            }

            // ذخیره نمونه برای دسترسی در متدها
            this.$watch('searchText', () => {
                this.hoveredIndex = -1;
            });
        },

        get filteredOptions() {
            if (!this.searchText.trim()) {
                return this.options;
            }
            
            const search = this.searchText.trim().toLowerCase();
            return this.options.filter(option => {
                const text = option.text.toLowerCase();
                const subtext = (option.subtext || '').toLowerCase();
                return text.includes(search) || subtext.includes(search);
            });
        },

        toggleDropdown() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.$nextTick(() => {
                    this.$el.querySelector('input').focus();
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
            this.closeDropdown();
            
            // ایجاد رویداد change برای استفاده در فرم
            this.$el.dispatchEvent(new CustomEvent('change', { 
                detail: { value: option.id, option: option }
            }));
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
            
            // اسکرول به گزینه انتخاب شده
            this.$nextTick(() => {
                const items = this.$el.querySelectorAll('.person-item');
                if (items[newIndex]) {
                    items[newIndex].scrollIntoView({ block: 'nearest' });
                }
            });
        }
    }
}
</script>
@endpush