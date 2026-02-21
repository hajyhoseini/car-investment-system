@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">لیست خودروها</h2>
                    
                    {{-- فقط کاربران با دسترسی ایجاد خودرو می‌تونن دکمه افزودن رو ببینن --}}
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

                <!-- فیلتر و جستجو (اختیاری) -->
                <div class="mb-6 flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" id="search" placeholder="جستجوی خودرو..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <select id="status-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all">همه وضعیت‌ها</option>
                            <option value="available">موجود</option>
                            <option value="sold">فروخته شده</option>
                            <option value="reserved">رزرو</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عنوان</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">برند</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">مدل</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">سال</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاریخ خرید</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قیمت خرید</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">وضعیت</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($cars as $index => $car)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cars->firstItem() + $index }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $car->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $car->brand }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $car->model }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $car->year }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $car->jalali_purchase_date ?? $car->purchase_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-blue-600">{{ number_format($car->purchase_price) }} ریال</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($car->status == 'available')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            موجود
                                        </span>
                                    @elseif($car->status == 'sold')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            فروخته شده
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            رزرو
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                        {{-- نمایش جزئیات - همه می‌تونن ببینن --}}
                                        @can('view cars')
                                            <a href="{{ route('cars.show', $car) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition" title="نمایش">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                        @endcan
                                        
                                        {{-- ویرایش - فقط کاربران با دسترسی edit cars --}}
                                        @can('edit cars')
                                            <a href="{{ route('cars.edit', $car) }}" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition" title="ویرایش">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                        @endcan
                                        
                                        {{-- فروش - فقط برای خودروهای موجود و کاربران با دسترسی sell cars --}}
                                        @if($car->status == 'available' && auth()->user()->can('sell cars'))
                                            <a href="{{ route('cars.sell', $car) }}" class="text-purple-600 hover:text-purple-900 p-1 rounded hover:bg-purple-50 transition" title="فروش">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </a>
                                        @endif
                                        
                                        {{-- حذف - فقط ادمین‌ها --}}
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
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- لینک‌های صفحه‌بندی --}}
                <div class="mt-4">
                    {{ $cars->links() }}
                </div>

                {{-- خلاصه آماری --}}
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">تعداد کل خودروها:</span>
                            <span class="font-bold mr-2">{{ $cars->total() }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">موجود:</span>
                            <span class="font-bold text-green-600 mr-2">{{ $cars->where('status', 'available')->count() }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">فروخته شده:</span>
                            <span class="font-bold text-red-600 mr-2">{{ $cars->where('status', 'sold')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // اضافه کردن تابع confirm برای حذف
    window.confirmDelete = function(form) {
        if (confirm('آیا از حذف این خودرو اطمینان دارید؟')) {
            form.submit();
        }
        return false;
    }

    // فیلتر ساده با جاوااسکریپت
    document.getElementById('search').addEventListener('keyup', function() {
        filterTable();
    });

    document.getElementById('status-filter').addEventListener('change', function() {
        filterTable();
    });

    function filterTable() {
        const searchText = document.getElementById('search').value.toLowerCase();
        const statusFilter = document.getElementById('status-filter').value;
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const title = row.cells[1]?.textContent.toLowerCase() || '';
            const brand = row.cells[2]?.textContent.toLowerCase() || '';
            const model = row.cells[3]?.textContent.toLowerCase() || '';
            const year = row.cells[4]?.textContent || '';
            const statusCell = row.cells[7]?.textContent.trim() || '';
            
            let status = 'available';
            if (statusCell.includes('فروخته')) status = 'sold';
            else if (statusCell.includes('رزرو')) status = 'reserved';

            const matchesSearch = title.includes(searchText) || 
                                 brand.includes(searchText) || 
                                 model.includes(searchText) || 
                                 year.includes(searchText);
            
            const matchesStatus = statusFilter === 'all' || status === statusFilter;

            row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
    }
</script>
@endpush