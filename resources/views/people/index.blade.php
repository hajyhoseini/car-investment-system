@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">مدیریت اشخاص</h2>
                    <a href="{{ route('people.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        <svg class="h-5 w-5 inline ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        افزودن شخص جدید
                    </a>
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

                <!-- فیلتر و جستجو -->
                <div class="mb-6 flex flex-wrap gap-4">
                    <div class="flex-1">
                        <input type="text" id="search" placeholder="جستجو بر اساس نام، کد ملی، تلفن..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <select id="type-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="all">همه انواع</option>
                            <option value="buyer">خریدار</option>
                            <option value="seller">فروشنده</option>
                            <option value="creditor">طلبکار</option>
                            <option value="debtor">بدهکار</option>
                            <option value="other">سایر</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">#</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تصویر</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نام</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">نوع</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">کد ملی</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تلفن</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">ایمیل</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($people as $index => $person)
                            <tr class="hover:bg-gray-50 transition" data-type="{{ $person->type }}">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $people->firstItem() + $index }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="{{ $person->avatar_url }}" alt="{{ $person->full_name }}" 
                                         class="w-10 h-10 rounded-full object-cover">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">
                                    {{ $person->display_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 rounded-full text-xs 
                                        @if($person->type == 'buyer') bg-blue-100 text-blue-800
                                        @elseif($person->type == 'seller') bg-green-100 text-green-800
                                        @elseif($person->type == 'creditor') bg-yellow-100 text-yellow-800
                                        @elseif($person->type == 'debtor') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $person->type_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $person->national_code ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $person->phone ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $person->email ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('people.show', $person) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition" title="نمایش">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('people.edit', $person) }}" class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition" title="ویرایش">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        <form action="{{ route('people.destroy', $person) }}" method="POST" class="inline" onsubmit="return confirm('آیا از حذف این شخص اطمینان دارید؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition" title="حذف">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $people->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // فیلتر ساده
    document.getElementById('search').addEventListener('keyup', filterTable);
    document.getElementById('type-filter').addEventListener('change', filterTable);

    function filterTable() {
        const searchText = document.getElementById('search').value.toLowerCase();
        const typeFilter = document.getElementById('type-filter').value;
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const type = row.dataset.type;
            const text = row.textContent.toLowerCase();
            
            const matchesSearch = searchText === '' || text.includes(searchText);
            const matchesType = typeFilter === 'all' || type === typeFilter;

            row.style.display = matchesSearch && matchesType ? '' : 'none';
        });
    }
</script>
@endpush