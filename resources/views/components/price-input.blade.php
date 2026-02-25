@props([
    'name' => 'amount',
    'label' => 'مبلغ (ریال)',
    'value' => '',
    'placeholder' => 'مثال: 1,000,000',
    'min' => 1000,
    'required' => false,
    'disabled' => false,
    'class' => '',
])

<div {{ $attributes->merge(['class' => 'price-input-wrapper']) }}>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <input
        type="text"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) ? (is_numeric(old($name, $value)) ? number_format(old($name, $value)) : old($name, $value)) : '' }}"
        placeholder="{{ $placeholder }}"
        min="{{ $min }}"
        @if($required) required @endif
        @if($disabled) disabled @endif
        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition price-input {{ $class }}"
        data-min="{{ $min }}"
    />
    
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

@pushonce('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // مدیریت همه inputهای قیمت
    document.querySelectorAll('.price-input').forEach(function(input) {
        // تنظیم مقدار اولیه
        formatInputValue(input);
        
        // رویداد تایپ کردن
        input.addEventListener('input', function(e) {
            let rawValue = this.value.replace(/[^0-9]/g, '');
            if (rawValue) {
                this.value = Number(rawValue).toLocaleString('en-US');
            } else {
                this.value = '';
            }
        });
        
        // رویداد وقتی input ترک می‌شود
        input.addEventListener('blur', function(e) {
            let rawValue = this.value.replace(/[^0-9]/g, '');
            let minValue = this.getAttribute('data-min');
            
            // اعتبارسنجی حداقل مقدار
            if (rawValue && minValue && Number(rawValue) < Number(minValue)) {
                this.value = Number(minValue).toLocaleString('en-US');
                // آپدیت مقدار اصلی
                updateRawValue(this, minValue);
            } else {
                updateRawValue(this, rawValue);
            }
        });
        
        // تابع برای آپدیت مقدار اصلی (برای ارسال به سرور)
        function updateRawValue(inputElement, rawValue) {
            // ایجاد یا آپدیت فیلد hidden برای ارسال مقدار بدون ویرگول
            let hiddenInput = document.querySelector(`input[name="${inputElement.id}_raw"]`);
            if (!hiddenInput) {
                hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = inputElement.id; // نام اصلی را برای فیلد hidden استفاده کن
                hiddenInput.id = `${inputElement.id}_raw`;
                inputElement.parentNode.appendChild(hiddenInput);
            }
            hiddenInput.value = rawValue;
            
            // تغییر name فیلد اصلی
            inputElement.removeAttribute('name');
        }
        
        // تابع برای فرمت کردن مقدار اولیه
        function formatInputValue(input) {
            let value = input.value.replace(/[^0-9]/g, '');
            if (value) {
                input.value = Number(value).toLocaleString('en-US');
            }
        }
    });
    
    // هنگام submit فرم
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function() {
            // اطمینان از اینکه مقادیر عددی به درستی ارسال می‌شوند
            document.querySelectorAll('.price-input').forEach(function(input) {
                let rawValue = input.value.replace(/[^0-9]/g, '');
                let hiddenInput = document.querySelector(`input[name="${input.id}_raw"]`);
                if (hiddenInput) {
                    hiddenInput.value = rawValue || '0';
                }
            });
        });
    });
});
</script>
@endpushonce