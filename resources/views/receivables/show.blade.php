@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">جزئیات مطالبه</h2>
                    <div class="flex gap-2">
                        <a href="{{ route('receivables.edit', $receivable) }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            ویرایش
                        </a>
                        <a href="{{ route('receivables.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            بازگشت
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- اطلاعات اصلی -->
                    <div class="bg-purple-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-purple-800">اطلاعات اصلی</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">عنوان:</span>
                                <div class="font-medium">{{ $receivable->title }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">توضیحات:</span>
                                <div class="text-gray-700">{{ $receivable->description ?? '—' }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">مبلغ کل:</span>
                                <div class="text-xl font-bold text-purple-700">{{ number_format($receivable->amount) }} ریال</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">نوع:</span>
                                <div class="font-medium">{{ $receivable->currency_type_label }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- وضعیت پرداخت -->
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-blue-800">وضعیت پرداخت</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">وضعیت:</span>
                                <div>
                                    <span class="px-3 py-1 rounded-full text-sm 
                                        @if($receivable->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($receivable->status == 'partially_paid') bg-blue-100 text-blue-800
                                        @elseif($receivable->status == 'paid') bg-green-100 text-green-800
                                        @elseif($receivable->status == 'overdue') bg-red-100 text-red-800
                                        @endif">
                                        {{ $receivable->status_label }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">پرداخت شده:</span>
                                <div class="text-lg font-bold text-green-600">{{ number_format($receivable->paid_amount) }} ریال</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">باقی‌مانده:</span>
                                <div class="text-lg font-bold text-orange-600">{{ number_format($receivable->remaining_amount) }} ریال</div>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                                @php $percentPaid = ($receivable->amount > 0) ? ($receivable->paid_amount / $receivable->amount) * 100 : 0; @endphp
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $percentPaid }}%"></div>
                            </div>
                            <div class="text-xs text-gray-500 text-left">{{ number_format($percentPaid, 1) }}% پرداخت شده</div>
                        </div>
                    </div>

                    <!-- اطلاعات شخص -->
                    @if($receivable->person)
                    <div class="bg-green-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-green-800">اطلاعات شخص</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">نام:</span>
                                <div class="font-medium">
                                    <a href="{{ route('people.show', $receivable->person) }}" class="text-blue-600 hover:underline">
                                        {{ $receivable->person->full_name }}
                                    </a>
                                </div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">کد ملی:</span>
                                <div>{{ $receivable->person->national_code ?? '—' }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">تلفن:</span>
                                <div>{{ $receivable->person->phone ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- تاریخ‌ها -->
                    <div class="bg-orange-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-orange-800">تاریخ‌ها</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600">تاریخ مطالبه:</span>
                                <div class="font-medium">{{ $receivable->receivable_date }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">تاریخ سررسید:</span>
                                <div class="font-medium {{ $receivable->status == 'overdue' ? 'text-red-600' : '' }}">
                                    {{ $receivable->due_date ?? '—' }}
                                    @if($receivable->status == 'overdue')
                                        <span class="text-red-600 text-sm">(سررسید گذشته)</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">تاریخ ثبت:</span>
                                <div>{{ $receivable->created_at->format('Y/m/d H:i') }}</div>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">ثبت شده توسط:</span>
                                <div>{{ $receivable->creator->name ?? '—' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- جزئیات اختصاصی (چک/طلا/دلار) -->
                    @if($receivable->currency_details)
                    <div class="md:col-span-2 bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">جزئیات {{ $receivable->currency_type_label }}</h3>
                        
                        @if($receivable->currency_type == 'check')
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <span class="text-sm text-gray-600">شماره چک:</span>
                                    <div class="font-medium">{{ $receivable->currency_details['check_number'] ?? '—' }}</div>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">نام بانک:</span>
                                    <div>{{ $receivable->currency_details['bank_name'] ?? '—' }}</div>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">تاریخ چک:</span>
                                    <div>{{ $receivable->currency_details['check_date'] ?? '—' }}</div>
                                </div>
                            </div>
                        @elseif($receivable->currency_type == 'gold')
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <span class="text-sm text-gray-600">وزن (گرم):</span>
                                    <div class="font-medium">{{ $receivable->currency_details['weight'] ?? '—' }}</div>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">عیار:</span>
                                    <div>{{ $receivable->currency_details['karat'] ?? '—' }}</div>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">توضیحات:</span>
                                    <div>{{ $receivable->currency_details['description'] ?? '—' }}</div>
                                </div>
                            </div>
                        @elseif($receivable->currency_type == 'dollar')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm text-gray-600">نرخ ارز (ریال):</span>
                                    <div class="font-medium">{{ number_format($receivable->currency_details['exchange_rate'] ?? 0) }} ریال</div>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-600">توضیحات:</span>
                                    <div>{{ $receivable->currency_details['description'] ?? '—' }}</div>
                                </div>
                            </div>
                        @endif
                    </div>
                    @endif

                    <!-- فایل ضمیمه -->
                    @if($receivable->attachments)
                    <div class="md:col-span-2 bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2 text-gray-800">فایل ضمیمه</h3>
                        <a href="{{ asset('storage/' . $receivable->attachments) }}" target="_blank" 
                           class="inline-flex items-center gap-2 text-blue-600 hover:underline">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            مشاهده فایل
                        </a>
                    </div>
                    @endif

                    <!-- یادداشت‌ها -->
                    @if($receivable->notes)
                    <div class="md:col-span-2 bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-2 text-gray-800">یادداشت‌ها</h3>
                        <p class="text-gray-700">{{ $receivable->notes }}</p>
                    </div>
                    @endif
                </div>

                <!-- دکمه ثبت پرداخت جدید -->
                @if($receivable->remaining_amount > 0)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <button onclick="openPaymentModal({{ $receivable->id }})" 
                            class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        ثبت پرداخت جدید
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- مودال ثبت پرداخت -->
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full">
        <h3 class="text-xl font-bold mb-4">ثبت پرداخت جدید</h3>
        <form id="paymentForm" method="POST" action="{{ route('receivables.payment', $receivable) }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">مبلغ پرداخت (ریال)</label>
                <input type="number" name="payment_amount" id="payment_amount" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" 
                       min="1000" max="{{ $receivable->remaining_amount }}" required>
                <p class="text-xs text-gray-500 mt-1">حداکثر قابل پرداخت: {{ number_format($receivable->remaining_amount) }} ریال</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ پرداخت</label>
                <input type="date" name="payment_date" id="payment_date" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" 
                       value="{{ now()->format('Y-m-d') }}" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">روش پرداخت</label>
                <select name="payment_method" id="payment_method" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required>
                    <option value="">انتخاب کنید</option>
                    <option value="cash">نقدی</option>
                    <option value="card">کارت به کارت</option>
                    <option value="check">چک</option>
                    <option value="transfer">حواله</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">یادداشت</label>
                <textarea name="payment_notes" rows="3" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500"></textarea>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closePaymentModal()" 
                        class="px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white rounded-lg transition">
                    انصراف
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-purple-500 hover:bg-purple-700 text-white rounded-lg transition">
                    ثبت پرداخت
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openPaymentModal(receivableId) {
        document.getElementById('paymentModal').classList.remove('hidden');
        document.getElementById('paymentModal').classList.add('flex');
    }
    
    function closePaymentModal() {
        document.getElementById('paymentModal').classList.add('hidden');
        document.getElementById('paymentModal').classList.remove('flex');
    }
</script>
@endpush
@endsection