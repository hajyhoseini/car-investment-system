@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h2 class="text-2xl font-bold mb-6">افزودن دارایی جدید</h2>

                <form method="POST" action="{{ route('assets.store') }}" id="assetForm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- نوع دارایی -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">نوع دارایی <span class="text-red-500">*</span></label>
                            <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                <option value="">انتخاب کنید</option>
                                <option value="bank" {{ old('type') == 'bank' ? 'selected' : '' }}>حساب بانکی</option>
                                <option value="dollar" {{ old('type') == 'dollar' ? 'selected' : '' }}>دلار</option>
                                <option value="gold" {{ old('type') == 'gold' ? 'selected' : '' }}>طلا</option>
                            </select>
                            @error('type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- نام دارایی -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">نام <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- مقدار (بسته به نوع تغییر می‌کند) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700" id="amountLabel">مقدار</label>
                            <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                            @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- ارزش به ریال (برای دلار و طلا) -->
                        <div id="valueField">
                            <label class="block text-sm font-medium text-gray-700">ارزش به ریال</label>
                            <input type="number" name="value" value="{{ old('value') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            @error('value') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- توضیحات -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">توضیحات</label>
                            <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('description') }}</textarea>
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end mt-6 space-x-2 rtl:space-x-reverse">
                        <a href="{{ route('assets.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
                            انصراف
                        </a>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
                            ثبت دارایی
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('type').addEventListener('change', function() {
    const type = this.value;
    const amountLabel = document.getElementById('amountLabel');
    const valueField = document.getElementById('valueField');
    
    switch(type) {
        case 'bank':
            amountLabel.textContent = 'موجودی (ریال)';
            valueField.style.display = 'none';
            break;
        case 'dollar':
            amountLabel.textContent = 'مقدار (دلار)';
            valueField.style.display = 'block';
            break;
        case 'gold':
            amountLabel.textContent = 'مقدار (گرم)';
            valueField.style.display = 'block';
            break;
        default:
            amountLabel.textContent = 'مقدار';
            valueField.style.display = 'block';
    }
});

// اجرای اولیه
document.getElementById('type').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection