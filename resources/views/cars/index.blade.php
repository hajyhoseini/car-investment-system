@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">لیست خودروها</h2>
                    <a href="{{ route('cars.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        افزودن خودرو جدید
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عنوان</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">برند</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">مدل</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">سال</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">قیمت خرید</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">وضعیت</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عملیات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($cars as $car)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $car->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $car->brand }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $car->model }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $car->year }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($car->purchase_price) }} ریال</td>
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
                                    <a href="{{ route('cars.show', $car) }}" class="text-blue-600 hover:text-blue-900 ml-2">نمایش</a>
                                    <a href="{{ route('cars.edit', $car) }}" class="text-green-600 hover:text-green-900 ml-2">ویرایش</a>
                                    @if($car->status == 'available')
                                    <a href="{{ route('cars.sell', $car) }}" class="text-purple-600 hover:text-purple-900 ml-2">فروش</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
