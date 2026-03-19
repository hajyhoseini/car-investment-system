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
                <!-- هدر + دکمه ایجاد -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">مدیریت تعهدات</h2>

                    @can('create liabilities')
                        <a href="{{ route('liabilities.create') }}"
                           class="inline-flex items-center gap-x-2 bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-md transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            ثبت تعهد جدید
                        </a>
                    @endcan
                </div>

                <!-- فیلترها -->
                <div class="mb-8 p-5 bg-gray-50 rounded-xl border border-gray-200">
                    <form method="GET" action="{{ route('liabilities.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <!-- فیلتر نوع تعهد -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">نوع تعهد</label>
                            <select name="type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                                <option value="">همه انواع</option>
                                <option value="debt" {{ request('type') == 'debt' ? 'selected' : '' }}>بدهی</option>
                                <option value="check" {{ request('type') == 'check' ? 'selected' : '' }}>چک</option>
                                <option value="installment" {{ request('type') == 'installment' ? 'selected' : '' }}>قسط</option>
                            </select>
                        </div>

                        <!-- فیلتر وضعیت -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">وضعیت</label>
                            <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                                <option value="">همه وضعیت‌ها</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>در انتظار</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>پرداخت شده</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>سررسید گذشته</option>
                            </select>
                        </div>

                        <!-- فیلتر شخص با کامپوننت searchable-select -->
                        <div class="filter-dropdown">
                            <x-searchable-select 
                                name="person_id"
                                label="شخص"
                                :options="$personOptions"
                                :selected="request('person_id')"
                                placeholder="همه اشخاص"
                            />
                        </div>

                        <!-- فیلتر تاریخ از -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">از تاریخ سررسید</label>
                            <input type="text" name="start_date" id="start_date" value="{{ request('start_date') }}" 
                                   class="jalali-datepicker w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition" 
                                   placeholder="مثال: ۱۴۰۲/۰۱/۰۱" autocomplete="off">
                        </div>

                        <!-- فیلتر تاریخ تا -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">تا تاریخ سررسید</label>
                            <input type="text" name="end_date" id="end_date" value="{{ request('end_date') }}" 
                                   class="jalali-datepicker w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition" 
                                   placeholder="مثال: ۱۴۰۲/۱۲/۲۹" autocomplete="off">
                        </div>

                        <!-- دکمه‌های فیلتر -->
                        <div class="md:col-span-5 flex justify-end gap-3 mt-2">
                            <a href="{{ route('liabilities.index') }}" 
                               class="px-5 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                <svg class="h-5 w-5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                حذف فیلترها
                            </a>
                            <button type="submit" 
                                    class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                <svg class="h-5 w-5 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                اعمال فیلتر
                            </button>
                        </div>
                    </form>
                </div>

                <!-- کارت‌های خلاصه با در نظر گرفتن فیلترها -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
                    <div class="bg-red-50 border border-red-100 rounded-xl p-5 text-center">
                        <div class="text-sm text-red-700 font-medium mb-1">جمع کل تعهدات</div>
                        <div class="text-3xl font-bold text-red-800">
                            {{ fa_currency($totalAmount) }}
                            <span class="text-xl">ریال</span>
                        </div>
                    </div>

                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-5 text-center">
                        <div class="text-sm text-amber-700 font-medium mb-1">باقیمانده پرداخت‌نشده</div>
                        <div class="text-3xl font-bold text-amber-800">
                            {{ fa_currency($totalRemaining) }}
                            <span class="text-xl">ریال</span>
                        </div>
                    </div>

                    <div class="bg-orange-50 border border-orange-100 rounded-xl p-5 text-center">
                        <div class="text-sm text-orange-700 font-medium mb-1">تعداد کل تعهدات</div>
                        <div class="text-3xl font-bold text-orange-800">{{ $totalCount }}</div>
                    </div>

                    <div class="bg-purple-50 border border-purple-100 rounded-xl p-5 text-center">
                        <div class="text-sm text-purple-700 font-medium mb-1">سررسید گذشته</div>
                        <div class="text-3xl font-bold text-purple-800">{{ $overdueCount }}</div>
                    </div>
                </div>

                <!-- سه ستون: بدهی‌ها - چک‌ها - اقساط -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- بدهی‌ها -->
                    <div>
                        <h3 class="text-xl font-semibold text-red-700 mb-5 flex items-center gap-x-2">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            بدهی‌ها
                            @if(request('type') && request('type') != 'debt')
                                <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-full">فیلتر شده</span>
                            @endif
                        </h3>

                        @if($liabilities->where('type', 'debt')->isEmpty())
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-8 text-center text-gray-500">
                                @if(request('type') && request('type') != 'debt')
                                    هیچ بدهی با فیلترهای انتخاب شده یافت نشد.
                                @else
                                    هنوز بدهی ثبت نشده است.
                                @endif
                            </div>
                        @else
                            <div class="space-y-5">
                                @foreach($liabilities->where('type', 'debt') as $liability)
                                    <div class="bg-white border border-red-200 rounded-xl p-6 hover:shadow-md transition-all">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="font-bold text-lg text-red-900">
                                                    @if($liability->person)
                                                        <a href="{{ route('people.show', $liability->person) }}" class="text-red-900 hover:text-red-700 hover:underline">
                                                            {{ $liability->person->full_name }}
                                                        </a>
                                                    @else
                                                        {{ $liability->creditor_name ?? '—' }}
                                                    @endif
                                                </h4>
                                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $liability->description ?? 'بدون توضیح' }}</p>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <div class="text-xl font-bold text-red-700">
                                                    {{ fa_currency($liability->remaining_amount) }}
                                                    <span class="text-base font-normal">ریال</span>
                                                </div>
                                                <div class="text-xs text-gray-600 mt-1">
                                                    از {{ fa_currency($liability->amount) }} ریال
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between mt-3">
                                            <div class="text-xs text-gray-600">
                                                سررسید: {{ jalali_date($liability->due_date) }}
                                            </div>

                                            <div class="flex items-center gap-x-3">
                                                @if($liability->status == 'pending')
                                                    <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">در انتظار</span>
                                                @elseif($liability->status == 'paid')
                                                    <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">پرداخت شده</span>
                                                @elseif($liability->status == 'overdue')
                                                    <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">سررسید گذشته</span>
                                                @endif

                                                @can('edit liabilities')
                                                    <a href="{{ route('liabilities.edit', $liability) }}"
                                                       class="text-blue-600 hover:text-blue-800 transition"
                                                       title="ویرایش">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- چک‌ها -->
                    <div>
                        <h3 class="text-xl font-semibold text-blue-700 mb-5 flex items-center gap-x-2">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            چک‌ها
                            @if(request('type') && request('type') != 'check')
                                <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-full">فیلتر شده</span>
                            @endif
                        </h3>

                        @if($liabilities->where('type', 'check')->isEmpty())
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-8 text-center text-gray-500">
                                @if(request('type') && request('type') != 'check')
                                    هیچ چکی با فیلترهای انتخاب شده یافت نشد.
                                @else
                                    هنوز چکی ثبت نشده است.
                                @endif
                            </div>
                        @else
                            <div class="space-y-5">
                                @foreach($liabilities->where('type', 'check') as $liability)
                                    <div class="bg-white border border-blue-200 rounded-xl p-6 hover:shadow-md transition-all">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="font-bold text-lg text-blue-900">
                                                    @if($liability->person)
                                                        <a href="{{ route('people.show', $liability->person) }}" class="text-blue-900 hover:text-blue-700 hover:underline">
                                                            {{ $liability->person->full_name }}
                                                        </a>
                                                    @else
                                                        {{ $liability->creditor_name ?? '—' }}
                                                    @endif
                                                </h4>
                                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $liability->description ?? 'بدون توضیح' }}</p>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <div class="text-xl font-bold text-blue-700">
                                                    {{ fa_currency($liability->remaining_amount) }}
                                                    <span class="text-base font-normal">ریال</span>
                                                </div>
                                                <div class="text-xs text-gray-600 mt-1">
                                                    از {{ fa_currency($liability->amount) }} ریال
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between mt-3">
                                            <div class="text-xs text-gray-600">
                                                سررسید: {{ jalali_date($liability->due_date) }}
                                            </div>

                                            <div class="flex items-center gap-x-3">
                                                @if($liability->status == 'pending')
                                                    <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">در انتظار</span>
                                                @elseif($liability->status == 'paid')
                                                    <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">پرداخت شده</span>
                                                @elseif($liability->status == 'overdue')
                                                    <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">سررسید گذشته</span>
                                                @endif

                                                @can('edit liabilities')
                                                    <a href="{{ route('liabilities.edit', $liability) }}"
                                                       class="text-blue-600 hover:text-blue-800 transition"
                                                       title="ویرایش">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- اقساط -->
                    <div>
                        <h3 class="text-xl font-semibold text-green-700 mb-5 flex items-center gap-x-2">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            اقساط
                            @if(request('type') && request('type') != 'installment')
                                <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-full">فیلتر شده</span>
                            @endif
                        </h3>

                        @if($liabilities->where('type', 'installment')->isEmpty())
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-8 text-center text-gray-500">
                                @if(request('type') && request('type') != 'installment')
                                    هیچ قسطی با فیلترهای انتخاب شده یافت نشد.
                                @else
                                    هنوز قسطی ثبت نشده است.
                                @endif
                            </div>
                        @else
                            <div class="space-y-5">
                                @foreach($liabilities->where('type', 'installment') as $liability)
                                    <div class="bg-white border border-green-200 rounded-xl p-6 hover:shadow-md transition-all">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="font-bold text-lg text-green-900">
                                                    @if($liability->person)
                                                        <a href="{{ route('people.show', $liability->person) }}" class="text-green-900 hover:text-green-700 hover:underline">
                                                            {{ $liability->person->full_name }}
                                                        </a>
                                                    @else
                                                        {{ $liability->creditor_name ?? '—' }}
                                                    @endif
                                                </h4>
                                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $liability->description ?? 'بدون توضیح' }}</p>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <div class="text-xl font-bold text-green-700">
                                                    {{ fa_currency($liability->remaining_amount) }}
                                                    <span class="text-base font-normal">ریال</span>
                                                </div>
                                                <div class="text-xs text-gray-600 mt-1">
                                                    از {{ fa_currency($liability->amount) }} ریال
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between mt-3">
                                            <div class="text-xs text-gray-600">
                                                سررسید: {{ jalali_date($liability->due_date) }}
                                            </div>

                                            <div class="flex items-center gap-x-3">
                                                @if($liability->status == 'pending')
                                                    <span class="px-2.5 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">در انتظار</span>
                                                @elseif($liability->status == 'paid')
                                                    <span class="px-2.5 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">پرداخت شده</span>
                                                @elseif($liability->status == 'overdue')
                                                    <span class="px-2.5 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">سررسید گذشته</span>
                                                @endif

                                                @can('edit liabilities')
                                                    <a href="{{ route('liabilities.edit', $liability) }}"
                                                       class="text-blue-600 hover:text-blue-800 transition"
                                                       title="ویرایش">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- صفحه‌بندی -->
                @if(method_exists($liabilities, 'links'))
                    <div class="mt-8">
                        {{ $liabilities->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    // تقویم شمسی برای فیلتر تاریخ‌ها
    $('.jalali-datepicker').persianDatepicker({
        format: 'YYYY/MM/DD',
        autoClose: true,
        initialValue: false,
        calendar: {
            persian: true
        }
    });
});
</script>
@endpush