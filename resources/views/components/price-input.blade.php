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

@php
    $id = $attributes->get('id') ?? $name;
    $hasError = $errors->has($name);
@endphp

<div {{ $attributes->merge(['class' => 'price-input-wrapper']) }}>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative rounded-md shadow-sm">
        <input
            type="text"
            id="{{ $id }}_display"
            value="{{ old($name, $value) ? (is_numeric(old($name, $value)) ? number_format(old($name, $value)) : old($name, $value)) : '' }}"
            placeholder="{{ $placeholder }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:border-transparent transition price-input {{ $class }} {{ $hasError ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 focus:ring-blue-500' }}"
            data-min="{{ $min }}"
            data-name="{{ $name }}"
            autocomplete="off"
        />
        
        <!-- فیلد مخفی برای ارسال مقدار واقعی به سرور -->
        <input type="hidden" name="{{ $name }}" id="{{ $id }}" value="{{ old($name, $value) }}">
    </div>
    
    @if($hasError)
        <p class="mt-1 text-sm text-red-600">{{ $errors->first($name) }}</p>
    @endif
    
    @if($min)
        <p class="mt-1 text-xs text-gray-500">حداقل مبلغ: {{ number_format($min) }} ریال</p>
    @endif
</div>

@pushonce('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // مدیریت همه inputهای قیمت
    document.querySelectorAll('.price-input').forEach(function(displayInput) {
        const hiddenInput = document.getElementById(displayInput.dataset.name);
        const minValue = parseInt(displayInput.dataset.min) || 0;
        
        if (!hiddenInput) return;
        
        // تنظیم مقدار اولیه
        if (hiddenInput.value) {
            displayInput.value = Number(hiddenInput.value).toLocaleString('en-US');
        }
        
        // رویداد تایپ کردن
        displayInput.addEventListener('input', function(e) {
            let rawValue = this.value.replace(/[^0-9]/g, '');
            
            if (rawValue) {
                // فرمت نمایش با ویرگول
                this.value = Number(rawValue).toLocaleString('en-US');
                // ذخیره مقدار واقعی در فیلد مخفی
                hiddenInput.value = rawValue;
            } else {
                this.value = '';
                hiddenInput.value = '';
            }
        });
        
        // رویداد وقتی input ترک می‌شود
        displayInput.addEventListener('blur', function(e) {
            let rawValue = parseInt(hiddenInput.value) || 0;
            
            // اعتبارسنجی حداقل مقدار
            if (rawValue < minValue) {
                hiddenInput.value = minValue;
                displayInput.value = Number(minValue).toLocaleString('en-US');
                
                // نمایش پیام خطا (اختیاری)
                showError(this, `حداقل مبلغ مجاز ${Number(minValue).toLocaleString('en-US')} ریال است`);
            }
        });
        
        // جلوگیری از ورود کاراکترهای غیرعددی
        displayInput.addEventListener('keydown', function(e) {
            // Allow: backspace, delete, tab, escape, enter, arrows, numbers, home, end
            if ([46, 8, 9, 27, 13, 110, 190].indexOf(e.keyCode) !== -1 ||
                // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                (e.keyCode === 67 && (e.ctrlKey === true || e.metaKey === true)) ||
                (e.keyCode === 86 && (e.ctrlKey === true || e.metaKey === true)) ||
                (e.keyCode === 88 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    });
    
    // تابع نمایش خطا
    function showError(inputElement, message) {
        // حذف خطاهای قبلی
        const existingError = inputElement.parentElement.parentElement.querySelector('.price-input-error');
        if (existingError) {
            existingError.remove();
        }
        
        // ایجاد خطای جدید
        const errorDiv = document.createElement('p');
        errorDiv.className = 'price-input-error mt-1 text-sm text-red-600';
        errorDiv.textContent = message;
        
        inputElement.parentElement.parentElement.appendChild(errorDiv);
        
        // حذف خودکار بعد از ۳ ثانیه
        setTimeout(() => {
            if (errorDiv.parentElement) {
                errorDiv.remove();
            }
        }, 3000);
    }
    
    // اعتبارسنجی قبل از ارسال فرم
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            let hasError = false;
            
            // بررسی همه فیلدهای قیمت
            document.querySelectorAll('.price-input').forEach(function(displayInput) {
                const hiddenInput = document.getElementById(displayInput.dataset.name);
                const minValue = parseInt(displayInput.dataset.min) || 0;
                
                if (!hiddenInput) return;
                
                const rawValue = parseInt(hiddenInput.value) || 0;
                
                // بررسی required
                if (displayInput.hasAttribute('required') && !rawValue) {
                    showError(displayInput, 'این فیلد الزامی است');
                    hasError = true;
                    e.preventDefault();
                }
                
                // بررسی حداقل مقدار
                if (rawValue && rawValue < minValue) {
                    showError(displayInput, `حداقل مبلغ مجاز ${Number(minValue).toLocaleString('en-US')} ریال است`);
                    hasError = true;
                    e.preventDefault();
                }
            });
        });
    });
});
</script>
@endpushonce