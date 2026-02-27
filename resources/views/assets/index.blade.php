@extends('layouts.app')

@section('content')
<div class="py-10 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
            <div class="p-6 md:p-8">
                <!-- هدر + دکمه‌های ایجاد -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <h2 class="text-2xl md:text-3xl font-bold text-gray-800">مدیریت دارایی‌ها</h2>

                    <div class="flex flex-wrap gap-3">
                        @can('create transactions')
                            <a href="{{ route('transactions.create', ['type' => 'income']) }}"
                               class="inline-flex items-center gap-x-2 bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-md transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                دریافت جدید
                            </a>

                            <a href="{{ route('transactions.create', ['type' => 'expense']) }}"
                               class="inline-flex items-center gap-x-2 bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-md transition focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                                پرداخت جدید
                            </a>
                            <!-- در هدر صفحه دارایی‌ها، کنار دکمه‌های دریافت و پرداخت -->
<a href="{{ route('expenses.create') }}"
   class="inline-flex items-center gap-x-2 bg-orange-600 hover:bg-orange-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-md transition focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2">
    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
    </svg>
    هزینه جدید
</a>
<!-- در هدر صفحه دارایی‌ها، کنار دکمه‌های دیگر -->
<a href="{{ route('receivables.create') }}"
   class="inline-flex items-center gap-x-2 bg-purple-600 hover:bg-purple-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-md transition focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z" />
    </svg>
    مطالبه جدید
</a>
                        @endcan

                        @can('create assets')
                            <a href="{{ route('assets.create') }}"
                               class="inline-flex items-center gap-x-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-md transition focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                افزودن دارایی جدید
                            </a>
                        @endcan
                    </div>
                </div>

                <!-- کارت‌های آماری خلاصه روزانه -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">
                    <div class="bg-green-50 border border-green-100 rounded-xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm text-green-700 font-medium">دریافتی امروز</div>
                                <div class="text-2xl font-bold text-green-800 mt-1">
                                    {{ fa_currency($todayIncome ?? 0) }}
                                </div>
                            </div>
                            <div class="bg-green-600 rounded-full p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-50 border border-red-100 rounded-xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm text-red-700 font-medium">پرداختی امروز</div>
                                <div class="text-2xl font-bold text-red-800 mt-1">
                                    {{ fa_currency($todayExpense ?? 0) }}
                                </div>
                            </div>
                            <div class="bg-red-600 rounded-full p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm text-blue-700 font-medium">موجودی حساب‌ها</div>
                                <div class="text-2xl font-bold text-blue-800 mt-1">
                                    {{ fa_currency($totalBankBalance ?? 0) }}
                                </div>
                            </div>
                            <div class="bg-blue-600 rounded-full p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M3 12h18M3 18h18" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm text-amber-700 font-medium">ارزش کل دارایی‌ها</div>
                                <div class="text-2xl font-bold text-amber-800 mt-1">
                                    {{ fa_currency($totalValue ?? 0) }}
                                </div>
                            </div>
                            <div class="bg-amber-600 rounded-full p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- لینک مشاهده همه تراکنش‌ها -->
                @can('view transactions')
                <div class="mb-8 flex justify-end">
                    <a href="{{ route('transactions.index') }}" 
                       class="text-blue-600 hover:text-blue-800 flex items-center gap-x-1 text-sm font-medium transition">
                        <span>مشاهده همه تراکنش‌ها</span>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                @endcan

                <!-- حساب‌های بانکی -->

<div class="mb-12">
    <h3 class="text-xl font-semibold text-gray-800 mb-5 flex items-center gap-x-2">
        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
        </svg>
        حساب‌های بانکی
    </h3>

    @if($bankAccountsWithBalance->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($bankAccountsWithBalance as $account)
                <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all relative group">
                    <!-- وضعیت فعال/غیرفعال -->
                    <div class="absolute top-3 left-3">
                        @if($account->is_active)
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">فعال</span>
                        @else
                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">غیرفعال</span>
                        @endif
                    </div>

                    <!-- محتوای اصلی -->
                    <div class="flex justify-between items-start mb-4 mt-6">
                        <div class="flex-1">
                            <h4 class="font-bold text-lg text-gray-900">{{ $account->name }}</h4>
                            
                            @if($account->bank_name)
                                <p class="text-sm font-medium text-blue-600 mt-1">{{ $account->bank_name }}</p>
                            @endif
                            
                            <!-- شماره حساب و کارت -->
                            <div class="mt-3 space-y-1.5">
                                @if($account->account_number)
                                    <div class="flex items-center gap-x-2 text-sm">
                                        <span class="text-gray-500 min-w-[70px]">شماره حساب:</span>
                                        <span class="font-mono text-gray-800 dir-ltr text-left">{{ $account->account_number }}</span>
                                        <button onclick="copyToClipboard('{{ $account->account_number }}')" 
                                                class="text-gray-400 hover:text-blue-600 transition" title="کپی">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif

                                @if($account->card_number)
                                    <div class="flex items-center gap-x-2 text-sm">
                                        <span class="text-gray-500 min-w-[70px]">شماره کارت:</span>
                                        <span class="font-mono text-gray-800 dir-ltr text-left">{{ wordwrap($account->card_number, 4, ' ', true) }}</span>
                                        <button onclick="copyToClipboard('{{ $account->card_number }}')" 
                                                class="text-gray-400 hover:text-blue-600 transition" title="کپی">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif

                                @if($account->sheba_number)
                                    <div class="flex items-center gap-x-2 text-sm">
                                        <span class="text-gray-500 min-w-[70px]">شماره شبا:</span>
                                        <span class="font-mono text-gray-800 dir-ltr text-left">{{ $account->sheba_number }}</span>
                                        <button onclick="copyToClipboard('{{ $account->sheba_number }}')" 
                                                class="text-gray-400 hover:text-blue-600 transition" title="کپی">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            @if($account->description)
                                <p class="text-sm text-gray-600 mt-3 line-clamp-2">{{ $account->description }}</p>
                            @endif
                        </div>

                        <!-- موجودی -->
<!-- موجودی -->
<div class="text-right shrink-0 mr-4">
    <div class="text-2xl font-bold text-blue-700">
        {{ fa_currency($account->current_balance) }}
    </div>
    <div class="text-xs text-gray-500 mt-1 flex flex-col items-end">
        <span>موجودی اولیه: {{ fa_currency($account->amount) }}</span>
        <span class="text-green-600">+ دریافتی: {{ fa_currency($account->incomingTransactions->sum('amount')) }}</span>
        <span class="text-red-600">- پرداختی: {{ fa_currency($account->outgoingTransactions->sum('amount')) }}</span>
    </div>
</div>
                    </div>

                    <!-- دکمه‌های عملیات -->
                    <div class="flex justify-end gap-2 mt-4 pt-3 border-t border-gray-100">
                        @can('view transactions')
                            <a href="{{ route('transactions.index', ['account_id' => $account->id]) }}"
                               class="inline-flex items-center gap-x-1.5 px-3 py-1.5 text-sm font-medium text-purple-700 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                                تراکنش‌ها
                            </a>
                        @endcan
                        
                        @can('edit assets')
                            <a href="{{ route('assets.edit', $account) }}"
                               class="inline-flex items-center gap-x-1.5 px-3 py-1.5 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                ویرایش
                            </a>
                        @endcan

                        @can('delete assets')
                            <form action="{{ route('assets.destroy', $account) }}" method="POST" class="inline" 
                                  onsubmit="return confirm('آیا از حذف این حساب اطمینان دارید؟ با حذف حساب، تمام تراکنش‌های مرتبط نیز حذف خواهند شد.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center gap-x-1.5 px-3 py-1.5 text-sm font-medium text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    حذف
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            @endforeach
        </div>

<!-- جمع کل موجودی حساب‌ها -->
<div class="mt-6 p-4 bg-blue-50 rounded-lg flex justify-between items-center">
    <span class="text-blue-800 font-medium">جمع کل موجودی حساب‌های بانکی:</span>
    <span class="text-2xl font-bold text-blue-800">{{ fa_currency($totalBankBalance) }}</span>
</div>
    @else
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-10 text-center text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <p class="text-lg font-medium text-gray-700 mb-2">هنوز هیچ حساب بانکی ثبت نشده است</p>
            <p class="text-sm text-gray-500">برای شروع، اولین حساب بانکی خود را ایجاد کنید</p>
            @can('create assets')
                <a href="{{ route('assets.create') }}" class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    ایجاد حساب بانکی
                </a>
            @endcan
        </div>
    @endif
</div>


                <!-- دلار و طلا -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- دلار -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-5 flex items-center gap-x-2">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            موجودی دلار
                        </h3>

                        @if($dollarAssets->isNotEmpty())
                            <div class="space-y-5">
                                @foreach($dollarAssets as $asset)
                                    <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="font-bold text-lg text-gray-900">{{ $asset->name }}</h4>
                                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $asset->description ?? 'بدون توضیح' }}</p>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <div class="text-2xl font-bold text-green-700">
                                                    {{ fa_currency($asset->amount, 2) }}
                                                    <span class="text-base font-normal">دلار</span>
                                                </div>
                                                @if($asset->value)
                                                    <div class="text-sm text-gray-600 mt-1">
                                                        ≈ {{ fa_currency($asset->value) }} ریال
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex justify-end">
                                            @can('edit assets')
                                                <a href="{{ route('assets.edit', $asset) }}"
                                                   class="text-blue-600 hover:text-blue-800 transition flex items-center gap-x-1.5 text-sm font-medium">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    ویرایش
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-10 text-center text-gray-500">
                                هنوز هیچ دارایی دلاری ثبت نشده است.
                            </div>
                        @endif
                    </div>

                    <!-- طلا -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-5 flex items-center gap-x-2">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            موجودی طلا
                        </h3>

                        @if($goldAssets->isNotEmpty())
                            <div class="space-y-5">
                                @foreach($goldAssets as $asset)
                                    <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-all">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h4 class="font-bold text-lg text-gray-900">{{ $asset->name }}</h4>
                                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $asset->description ?? 'بدون توضیح' }}</p>
                                            </div>
                                            <div class="text-right shrink-0">
                                                <div class="text-2xl font-bold text-yellow-700">
                                                    {{ fa_currency($asset->amount, 2) }}
                                                    <span class="text-base font-normal">گرم</span>
                                                </div>
                                                @if($asset->value)
                                                    <div class="text-sm text-gray-600 mt-1">
                                                        ≈ {{ fa_currency($asset->value) }} ریال
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex justify-end">
                                            @can('edit assets')
                                                <a href="{{ route('assets.edit', $asset) }}"
                                                   class="text-blue-600 hover:text-blue-800 transition flex items-center gap-x-1.5 text-sm font-medium">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    ویرایش
                                                </a>
                                            @endcan
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-10 text-center text-gray-500">
                                هنوز هیچ دارایی طلایی ثبت نشده است.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // ایجاد المان موقت برای نمایش پیام
            var toast = document.createElement('div');
            toast.textContent = 'کپی شد';
            toast.style.position = 'fixed';
            toast.style.bottom = '20px';
            toast.style.left = '50%';
            toast.style.transform = 'translateX(-50%)';
            toast.style.backgroundColor = '#10b981';
            toast.style.color = 'white';
            toast.style.padding = '8px 16px';
            toast.style.borderRadius = '8px';
            toast.style.fontSize = '14px';
            toast.style.zIndex = '9999';
            toast.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
            document.body.appendChild(toast);
            
            setTimeout(function() {
                toast.remove();
            }, 2000);
        }).catch(function(err) {
            console.error('خطا در کپی: ', err);
        });
    }
</script>
@endpush