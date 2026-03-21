@extends('layouts.app')

@section('styles')
<style>
    .filter-dropdown {
        min-width: 250px;
    }
</style>
@endsection

@section('content')
<div class="py-10 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
            <div class="p-6 md:p-8">
                <!-- Header + دکمه ایجاد -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">مدیریت سرمایه‌گذاران</h2>

                    @can('create investors')
                        <a href="{{ route('investors.create') }}"
                           class="inline-flex items-center gap-x-2 bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-md transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            سرمایه‌گذار جدید
                        </a>
                    @endcan
                </div>

                <!-- فیلترها -->
                <div class="mb-8 p-5 bg-gray-50 rounded-xl border border-gray-200">
                    <form method="GET" action="{{ route('investors.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- جستجو بر اساس نام -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">جستجو</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                                   placeholder="نام، کد ملی یا تلفن...">
                        </div>

                        <!-- فیلتر بر اساس حداقل سرمایه -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">حداقل سرمایه (ریال)</label>
                            <input type="text" name="min_investment" id="min_investment" value="{{ request('min_investment') }}" 
                                   class="price-input w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                                   placeholder="مثال: ۱۰,۰۰۰,۰۰۰">
                        </div>

                        <!-- فیلتر بر اساس حداکثر سرمایه -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">حداکثر سرمایه (ریال)</label>
                            <input type="text" name="max_investment" id="max_investment" value="{{ request('max_investment') }}" 
                                   class="price-input w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition" 
                                   placeholder="مثال: ۱۰۰,۰۰۰,۰۰۰">
                        </div>

                        <!-- فیلتر بر اساس وضعیت (فعال/غیرفعال) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت</label>
                            <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                                <option value="">همه</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>فعال</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غیرفعال</option>
                            </select>
                        </div>

                        <!-- دکمه‌های فیلتر -->
                        <div class="md:col-span-4 flex justify-end gap-3 mt-2">
                            <a href="{{ route('investors.index') }}" 
                               class="px-5 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                <svg class="h-5 w-5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                حذف فیلترها
                            </a>
                            <button type="submit" 
                                    class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                <svg class="h-5 w-5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                اعمال فیلتر
                            </button>
                        </div>
                    </form>
                </div>

                <!-- کارت‌های آماری با در نظر گرفتن فیلترها -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 text-center">
                        <div class="text-sm text-blue-700 font-medium mb-1">تعداد سرمایه‌گذاران</div>
                        <div class="text-3xl font-bold text-blue-800">{{ $investors->total() }}</div>
                    </div>

                    <div class="bg-green-50 border border-green-100 rounded-xl p-5 text-center">
                        <div class="text-sm text-green-700 font-medium mb-1">کل سرمایه‌گذاری</div>
                        <div class="text-3xl font-bold text-green-800">
                            {{ number_format($totalInvested ?? $investors->sum('total_invested')) }} <span class="text-xl">ریال</span>
                        </div>
                    </div>

                    <div class="bg-purple-50 border border-purple-100 rounded-xl p-5 text-center">
                        <div class="text-sm text-purple-700 font-medium mb-1">میانگین سرمایه هر نفر</div>
                        <div class="text-3xl font-bold text-purple-800">
                            {{ number_format($averageInvested ?? $investors->avg('total_invested') ?? 0) }} <span class="text-xl">ریال</span>
                        </div>
                    </div>
                </div>

                <!-- پیام‌ها -->
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

                <!-- جدول -->
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            32
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">#</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">نام و نام خانوادگی</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">کد ملی</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">تلفن</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700 hidden md:table-cell">ایمیل</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">کل سرمایه</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">تعداد سرمایه‌گذاری</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">وضعیت</th>
                                <th class="px-4 py-3.5 text-right text-sm font-semibold text-gray-700">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($investors as $index => $investor)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">{{ $investors->firstItem() + $index }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $investor->full_name }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">{{ $investor->national_code }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">{{ $investor->phone }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600 hidden md:table-cell">{{ $investor->email ?? '—' }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-bold text-green-700">
                                        {{ number_format($investor->total_invested ?? 0) }} ریال
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $investor->investments_count ?? $investor->investments->count() }} مورد
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap">
                                        @if($investor->is_active ?? true)
                                            <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">فعال</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">غیرفعال</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-x-3">
                                            @can('view investors')
                                                <a href="{{ route('investors.show', $investor) }}" class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50 transition" title="نمایش جزئیات">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                            @endcan

                                            @can('edit investors')
                                                <a href="{{ route('investors.edit', $investor) }}" class="text-green-600 hover:text-green-800 p-1 rounded hover:bg-green-50 transition" title="ویرایش">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                            @endcan

                                            @can('delete investors')
                                                <form action="{{ route('investors.destroy', $investor) }}" method="POST" class="inline" onsubmit="return confirm('آیا از حذف سرمایه‌گذار {{ $investor->full_name }} اطمینان دارید؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 p-1 rounded hover:bg-red-50 transition" title="حذف">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-10 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-4a2 2 0 00-2 2v2m-4-6H4" />
                                        </svg>
                                        <p class="mt-2 text-sm">هیچ سرمایه‌گذاری ثبت نشده است.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6 flex justify-center">
                    {{ $investors->appends(request()->query())->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // فرمت عدد با ویرگول برای فیلدهای مبلغ
    const priceInputs = document.querySelectorAll('.price-input');
    
    priceInputs.forEach(function(input) {
        // تنظیم مقدار اولیه
        if (input.value && !isNaN(input.value)) {
            input.value = Number(input.value).toLocaleString('en-US');
        }
        
        input.addEventListener('input', function(e) {
            let value = this.value.replace(/[^\d]/g, '');
            if (value) {
                this.value = Number(value).toLocaleString('en-US');
            } else {
                this.value = '';
            }
        });
        
        input.addEventListener('blur', function(e) {
            let value = this.value.replace(/[^\d]/g, '');
            if (value) {
                this.value = Number(value).toLocaleString('en-US');
            }
        });
    });
});
</script>
@endpush