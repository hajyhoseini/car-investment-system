@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">ویرایش مطالبه</h2>
                    <a href="{{ route('receivables.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        بازگشت
                    </a>
                </div>

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('receivables.update', $receivable) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- فیلدها مشابه create با مقادیر old و $receivable -->
                    <!-- برای جلوگیری از تکرار، از همان ساختار create استفاده کن با مقادیر پیش‌فرض -->
                    <!-- ... (کد مشابه create با اضافه کردن value="{{ old('field', $receivable->field) }}" ) ... -->
                </form>
            </div>
        </div>
    </div>
</div>
@endsection