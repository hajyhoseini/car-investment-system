@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
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

                <!-- فیلتر و جستجو پیشرفته - با سلکت معمولی -->
                <div class="mb-6 bg-gray-50 p-4 rounded-xl border-2 border-gray-300">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <input type="text" id="search" placeholder="جستجوی خودرو..." 
                                   class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <select id="status-filter" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="all">همه وضعیت‌ها</option>
                                <option value="available">موجود</option>
                                <option value="sold">فروخته شده</option>
                                <option value="reserved">رزرو</option>
                            </select>
                        </div>
                        <div>
                            <select id="brand-filter" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="all">همه برندها</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand }}">{{ $brand }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select id="priority-filter" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="all">همه اولویت‌ها</option>
                                <option value="high">زیاد</option>
                                <option value="medium">متوسط</option>
                                <option value="low">کم</option>
                            </select>
                        </div>
                        <div>
                            <select id="owner-filter" class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="all">همه مالک‌ها</option>
                                <option value="personal">شخصی</option>
                                <option value="exhibition">نمایشگاه</option>
                                <option value="consignment">امانی</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mt-3">
                        <button id="reset-filters" class="text-sm text-gray-500 hover:text-gray-700">
                            🗑️ حذف فیلترها
                        </button>
                        <span id="filter-count" class="text-sm text-gray-500"></span>
                    </div>
                </div>

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
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400"> قیمت خرید(ریال)</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400"> قیمت بازار(ریال)</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">قیمت هلدینگ</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">حدپایین(ریال) </th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">وضعیت سرمایه</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">وضعیت</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border-2 border-gray-400">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white" id="table-body">
                            @foreach($cars as $index => $car)
                            <tr class="hover:bg-gray-50 transition border-b border-gray-300" 
                                data-status="{{ $car->status }}"
                                data-brand="{{ $car->brand }}"
                                data-priority="{{ $car->purchase_priority ?? 'medium' }}"
                                data-owner="{{ $car->owner_type ?? '' }}">
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
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->brand }}</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->model }}</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ fa_number($car->year) }}</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ fa_number($car->kilometers) }} km</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->color ?? '-' }}</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->transmission ?? '-' }}</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->fuel_type ?? '-' }}</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">
                                    @php
                                        $priorityColors = ['high' => 'red', 'medium' => 'orange', 'low' => 'green'];
                                        $priorityTexts = ['high' => 'زیاد', 'medium' => 'متوسط', 'low' => 'کم'];
                                        $priority = $car->purchase_priority ?? 'medium';
                                    @endphp
                                    <span class="px-2 py-1 text-xs rounded-full bg-{{ $priorityColors[$priority] }}-100 text-{{ $priorityColors[$priority] }}-800">
                                        {{ $priorityTexts[$priority] }}
                                    </span>
                                </div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->body_condition_persian ?? '-' }}</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->technical_condition_persian ?? '-' }}</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->document_status_persian ?? '-' }}</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->storage_location_persian ?? '-' }}</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->owner_type_persian ?? '-' }}</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->customer_type_persian ?? '-' }}</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ $car->phone_number ?? '-' }}</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ jalali_date($car->inquiry_date) ?? '-' }}</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ jalali_date($car->purchase_date) ?? '-' }}</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">
                                    <div class="flex flex-col items-center">
                                        <span>{{ jalali_date($car->created_at) }}</span>
                                        <span class="text-xs text-gray-500">{{ jalali_time($car->created_at) }}</span>
                                    </div>
                                </div>
                                <td class="px-3 py-4 whitespace-nowrap font-bold text-blue-600 border-2 border-gray-300 text-center">{{ fa_currency($car->purchase_price) }}</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ fa_currency($car->market_price) ?? '-' }}</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ fa_currency($car->holding_price) ?? '-' }}</div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">{{ fa_currency($car->min_price) ?? '-' }}</div>
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
                                </div>
                                <td class="px-3 py-4 whitespace-nowrap border-2 border-gray-300 text-center">
                                    @if($car->status == 'available')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">موجود</span>
                                    @elseif($car->status == 'sold')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">فروخته شده</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">رزرو</span>
                                    @endif
                                </div>
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
                                </div>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $cars->links() }}
                </div>

                <div class="mt-6 p-4 bg-gray-50 rounded-lg border-2 border-gray-300">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4 text-sm text-center">
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

    function filterTable() {
        const searchText = document.getElementById('search').value.toLowerCase();
        const statusFilter = document.getElementById('status-filter').value;
        const brandFilter = document.getElementById('brand-filter').value;
        const priorityFilter = document.getElementById('priority-filter').value;
        const ownerFilter = document.getElementById('owner-filter').value;
        
        const rows = document.querySelectorAll('#table-body tr');
        let visibleCount = 0;

        rows.forEach(row => {
            const status = row.dataset.status;
            const brand = row.dataset.brand;
            const priority = row.dataset.priority;
            const owner = row.dataset.owner;
            const rowText = row.textContent.toLowerCase();
            
            const matchesSearch = searchText === '' || rowText.includes(searchText);
            const matchesStatus = statusFilter === 'all' || status === statusFilter;
            const matchesBrand = brandFilter === 'all' || brand === brandFilter;
            const matchesPriority = priorityFilter === 'all' || priority === priorityFilter;
            const matchesOwner = ownerFilter === 'all' || owner === ownerFilter;
            
            const isVisible = matchesSearch && matchesStatus && matchesBrand && matchesPriority && matchesOwner;
            row.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCount++;
        });
        
        document.getElementById('filter-count').innerHTML = visibleCount + ' مورد یافت شد';
    }

    function sortTable(columnIndex) {
        const table = document.querySelector('#table-body');
        const rows = Array.from(table.querySelectorAll('tr'));
        
        if (currentSortColumn === columnIndex) {
            sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            currentSortColumn = columnIndex;
            sortDirection = 'asc';
        }
        
        rows.sort((a, b) => {
            let aValue = getCellValue(a, columnIndex);
            let bValue = getCellValue(b, columnIndex);
            
            if (columnIndex === 5) {
                aValue = parseInt(aValue) || 0;
                bValue = parseInt(bValue) || 0;
            } else {
                aValue = aValue.toString();
                bValue = bValue.toString();
            }
            
            return sortDirection === 'asc' ? (aValue > bValue ? 1 : -1) : (aValue < bValue ? 1 : -1);
        });
        
        rows.forEach(row => table.appendChild(row));
        updateSortIndicators(columnIndex);
    }

    function getCellValue(row, columnIndex) {
        const cells = row.querySelectorAll('td');
        return cells[columnIndex] ? cells[columnIndex].textContent.trim() : '';
    }

    function updateSortIndicators(activeColumn) {
        const headers = document.querySelectorAll('thead th');
        headers.forEach((header, index) => {
            if (index === activeColumn) {
                header.innerHTML = header.innerHTML.replace(/ ⬍| ⬆| ⬇/g, '') + (sortDirection === 'asc' ? ' ⬆' : ' ⬇');
            } else {
                header.innerHTML = header.innerHTML.replace(/ ⬆| ⬇/g, ' ⬍');
            }
        });
    }

    // Event Listeners
    document.getElementById('search').addEventListener('keyup', filterTable);
    document.getElementById('status-filter').addEventListener('change', filterTable);
    document.getElementById('brand-filter').addEventListener('change', filterTable);
    document.getElementById('priority-filter').addEventListener('change', filterTable);
    document.getElementById('owner-filter').addEventListener('change', filterTable);
    
    document.getElementById('reset-filters').addEventListener('click', function() {
        document.getElementById('search').value = '';
        document.getElementById('status-filter').value = 'all';
        document.getElementById('brand-filter').value = 'all';
        document.getElementById('priority-filter').value = 'all';
        document.getElementById('owner-filter').value = 'all';
        filterTable();
    });

    filterTable();
</script>
@endpush