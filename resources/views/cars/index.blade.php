@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                    <h2 class="text-2xl font-bold">لیست خودروها</h2>
                    
                    @can('create cars')
                        <a href="{{ route('cars.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition transform hover:scale-105 flex items-center">
                            <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            افزودن خودرو جدید
                        </a>
                    @endcan
                </div>

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

                <!-- فیلتر و جستجو پیشرفته -->
                <form method="GET" action="{{ route('cars.index') }}" id="filter-form">
                    <div class="mb-6 bg-gray-50 p-4 rounded-xl border-2 border-gray-300">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div class="relative">
                                <input type="text" name="search" id="search" placeholder="جستجوی خودرو..." 
                                       value="{{ request('search') }}"
                                       class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <select name="status" id="status-filter" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>همه وضعیت‌ها</option>
                                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>موجود</option>
                                    <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>فروخته شده</option>
                                    <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>رزرو</option>
                                </select>
                            </div>
                            <div>
                                <select name="brand" id="brand-filter" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="all" {{ request('brand') == 'all' ? 'selected' : '' }}>همه برندها</option>
                                    @foreach($brands ?? [] as $brand)
                                        <option value="{{ $brand }}" {{ request('brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select name="priority" id="priority-filter" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="all" {{ request('priority') == 'all' ? 'selected' : '' }}>همه اولویت‌ها</option>
                                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>زیاد</option>
                                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>متوسط</option>
                                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>کم</option>
                                </select>
                            </div>
                            <div>
                                <select name="owner" id="owner-filter" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="all" {{ request('owner') == 'all' ? 'selected' : '' }}>همه مالک‌ها</option>
                                    <option value="personal" {{ request('owner') == 'personal' ? 'selected' : '' }}>شخصی</option>
                                    <option value="exhibition" {{ request('owner') == 'exhibition' ? 'selected' : '' }}>نمایشگاه</option>
                                    <option value="consignment" {{ request('owner') == 'consignment' ? 'selected' : '' }}>امانی</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mt-3">
                            <div class="flex gap-2">
                                <button type="submit" id="search-btn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition transform hover:scale-105 flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    جستجو
                                </button>
                                <button type="reset" id="reset-filters" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition transform hover:scale-105 flex items-center gap-2">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    حذف فیلترها
                                </button>
                            </div>
                            <span id="filter-count" class="text-sm text-gray-500 bg-white px-3 py-1 rounded-full">
                                <span class="font-bold">{{ fa_number($cars->total()) }}</span> مورد یافت شد
                            </span>
                        </div>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full border-2 border-gray-400">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 border-2 border-gray-400" onclick="sortTable(0)"># ⬍</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">تصویر</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 border-2 border-gray-400" onclick="sortTable(2)">عنوان ⬍</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 border-2 border-gray-400" onclick="sortTable(3)">برند ⬍</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 border-2 border-gray-400" onclick="sortTable(4)">مدل ⬍</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider cursor-pointer hover:bg-gray-200 border-2 border-gray-400" onclick="sortTable(5)">سال ⬍</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">کارکرد</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">رنگ</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">گیربکس</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">سوخت</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">اولویت</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">وضعیت بدنه</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">وضعیت فنی</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">وضعیت سند</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">محل نگهداری</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">مالک</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">نوع مشتری</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">تلفن</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">تاریخ استعلام</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">تاریخ خرید</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">تاریخ ثبت</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">قیمت خرید(ریال)</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">قیمت بازار(ریال)</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">قیمت هلدینگ</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">حدپایین(ریال)</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">وضعیت سرمایه</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">وضعیت</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white" id="table-body">
                            @forelse($cars as $index => $car)
                            <tr class="hover:bg-gray-50 transition border-b border-gray-300">
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500 border-2 border-gray-300 text-center">{{ fa_number($cars->firstItem() + $index) }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">
                                    <div class="flex justify-center">
                                        <a href="{{ route('cars.show', $car) }}">
                                            <img src="{{ $car->thumbnail_url }}" class="w-16 h-12 object-cover rounded-lg border border-gray-200 hover:border-blue-500 transition">
                                        </a>
                                    </div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap font-medium border-2 border-gray-300 text-center">
                                    <a href="{{ route('cars.show', $car) }}" class="text-blue-600 hover:text-blue-900">{{ $car->title }}</a>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->brand }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->model }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ fa_number($car->year) }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ fa_number($car->kilometers) }} km</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->color ?? '-' }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->transmission ?? '-' }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->fuel_type ?? '-' }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">
                                    @php
                                        $priorityColors = ['high' => 'red', 'medium' => 'orange', 'low' => 'green'];
                                        $priorityTexts = ['high' => 'زیاد', 'medium' => 'متوسط', 'low' => 'کم'];
                                        $priority = $car->purchase_priority ?? 'medium';
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $priorityColors[$priority] }}-100 text-{{ $priorityColors[$priority] }}-800">
                                        {{ $priorityTexts[$priority] }}
                                    </span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->body_condition_persian ?? '-' }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->technical_condition_persian ?? '-' }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->document_status_persian ?? '-' }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->storage_location_persian ?? '-' }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->owner_type_persian ?? '-' }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->customer_type_persian ?? '-' }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->phone_number ?? '-' }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ jalali_date($car->inquiry_date) ?? '-' }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ jalali_date($car->purchase_date) ?? '-' }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">
                                    <div class="flex flex-col items-center">
                                        <span>{{ jalali_date($car->created_at) }}</span>
                                        <span class="text-xs text-gray-500">{{ jalali_time($car->created_at) }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap font-bold text-blue-600 border-2 border-gray-300 text-center">{{ fa_currency($car->purchase_price) }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ fa_currency($car->market_price) ?? '-' }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ fa_currency($car->holding_price) ?? '-' }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ fa_currency($car->min_price) ?? '-' }}</td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">
                                    @php
                                        $fundedPercentage = $car->purchase_price > 0 ? ($car->total_invested / $car->purchase_price) * 100 : 0;
                                    @endphp
                                    <div class="flex flex-col min-w-[150px]">
                                        <div class="flex justify-between text-xs mb-1">
                                            <span>{{ fa_number($fundedPercentage, 1) }}%</span>
                                            <span>{{ fa_currency($car->total_invested) }}</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ min($fundedPercentage, 100) }}%"></div>
                                        </div>
                                        @if($fundedPercentage >= 100)
                                            <span class="text-xs text-green-600 mt-1">✓ تکمیل شده</span>
                                        @else
                                            <span class="text-xs text-blue-600 mt-1">{{ fa_currency($car->purchase_price - $car->total_invested) }} باقی</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">
                                    @if($car->status == 'available')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">موجود</span>
                                    @elseif($car->status == 'sold')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">فروخته شده</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">رزرو</span>
                                    @endif
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm font-medium border-2 border-gray-300 text-center">
                                    <div class="flex items-center justify-center space-x-2 rtl:space-x-reverse">
                                        @can('view cars')
                                            <a href="{{ route('cars.show', $car) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition" title="نمایش">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                        @endcan
                                        @can('edit cars')
                                            <a href="{{ route('cars.edit', $car) }}" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition" title="ویرایش">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                        @endcan
                                        @if($car->status == 'available' && auth()->user()->can('sell cars'))
                                            <a href="{{ route('cars.sell', $car) }}" class="text-purple-600 hover:text-purple-900 p-1 rounded hover:bg-purple-50 transition" title="فروش">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </a>
                                        @endif
                                        @can('delete cars')
                                            <form action="{{ route('cars.destroy', $car) }}" method="POST" class="inline" onsubmit="return confirm('آیا از حذف این خودرو اطمینان دارید؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition" title="حذف">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="28" class="px-3 py-8 text-center text-gray-500 border-2 border-gray-300">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-lg">خودرویی یافت نشد</p>
                                    <p class="text-sm mt-1">لطفاً معیارهای جستجو را تغییر دهید</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $cars->appends(request()->query())->links() }}
                </div>

                <div class="mt-6 p-4 bg-gray-50 rounded-lg border-2 border-gray-300">
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 text-sm text-center">
                        <div><span class="text-gray-600">تعداد کل:</span> <span class="font-bold mr-2">{{ fa_number($cars->total()) }}</span></div>
                        <div><span class="text-gray-600">موجود:</span> <span class="font-bold text-green-600 mr-2">{{ fa_number($cars->where('status', 'available')->count()) }}</span></div>
                        <div><span class="text-gray-600">فروخته شده:</span> <span class="font-bold text-red-600 mr-2">{{ fa_number($cars->where('status', 'sold')->count()) }}</span></div>
                        <div><span class="text-gray-600">رزرو:</span> <span class="font-bold text-yellow-600 mr-2">{{ fa_number($cars->where('status', 'reserved')->count()) }}</span></div>
                        <div><span class="text-gray-600">سرمایه کل:</span> <span class="font-bold text-blue-600 mr-2">{{ fa_currency($cars->sum('purchase_price')) }}</span></div>
                        <div><span class="text-gray-600">سرمایه جذب شده:</span> <span class="font-bold text-green-600 mr-2">{{ fa_currency($cars->sum('total_invested')) }}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentSortColumn = -1;
    let sortDirection = 'asc';
    let sortColumn = null;

    function filterTable() {
        document.getElementById('filter-form').submit();
    }

    function sortTable(columnIndex) {
        const columnNames = ['id', 'image', 'title', 'brand', 'model', 'year'];
        const columnName = columnNames[columnIndex];
        
        if (sortColumn === columnName) {
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            sortColumn = columnName;
            sortDirection = 'asc';
        }
        
        const url = new URL(window.location.href);
        url.searchParams.set('sort', columnName);
        url.searchParams.set('direction', sortDirection);
        window.location.href = url.toString();
    }

    // Event Listeners برای فیلترها
    const searchInput = document.getElementById('search');
    const searchBtn = document.getElementById('search-btn');
    const resetBtn = document.getElementById('reset-filters');
    
    // جستجو با دکمه
    if (searchBtn) {
        searchBtn.addEventListener('click', function(e) {
            e.preventDefault();
            filterTable();
        });
    }
    
    // جستجو با Enter
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                filterTable();
            }
        });
    }
    
    // فیلترهای select
    const statusFilter = document.getElementById('status-filter');
    const brandFilter = document.getElementById('brand-filter');
    const priorityFilter = document.getElementById('priority-filter');
    const ownerFilter = document.getElementById('owner-filter');
    
    if (statusFilter) statusFilter.addEventListener('change', filterTable);
    if (brandFilter) brandFilter.addEventListener('change', filterTable);
    if (priorityFilter) priorityFilter.addEventListener('change', filterTable);
    if (ownerFilter) ownerFilter.addEventListener('change', filterTable);
    
    // دکمه حذف فیلترها
    if (resetBtn) {
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = window.location.pathname;
        });
    }
</script>
@endpush