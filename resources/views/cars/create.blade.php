@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">افزودن خودرو جدید</h2>
                    <a href="{{ route('cars.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        بازگشت به لیست
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

                <form method="POST" action="{{ route('cars.store') }}" enctype="multipart/form-data" id="carForm" x-data="wizardForm()">
                    @csrf

                    <!-- Progress Bar -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <template x-for="(step, index) in steps" :key="step.id">
                                        <div class="flex-1 relative">
                                            <div class="flex flex-col items-center">
                                                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold transition-all duration-300 text-sm"
                                                     :class="{
                                                         'bg-green-600': step.completed,
                                                         'bg-blue-600 ring-4 ring-blue-200': currentStep === step.id && !step.completed,
                                                         'bg-gray-300': currentStep !== step.id && !step.completed
                                                     }">
                                                    <span x-show="step.completed" class="text-white text-lg">✓</span>
                                                    <span x-show="!step.completed" x-text="step.id" class="text-gray-700"></span>
                                                </div>
                                                <div class="mt-2 text-sm font-medium text-center"
                                                     :class="{
                                                         'text-blue-600': currentStep === step.id,
                                                         'text-green-600': step.completed,
                                                         'text-gray-500': currentStep !== step.id && !step.completed
                                                     }"
                                                     x-text="step.name">
                                                </div>
                                                <div class="text-xs text-gray-400 mt-1" x-show="step.completed" x-text="step.fields.length + ' مورد ثبت شد'"></div>
                                            </div>
                                            <div class="absolute top-5 left-0 w-full h-0.5 -z-10"
                                                 x-show="index < steps.length - 1"
                                                 :class="{
                                                     'bg-green-600': step.completed,
                                                     'bg-gray-300': !step.completed
                                                 }">
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- صفحه اول: اطلاعات پایه (اجباری) -->
                    <div x-show="currentStep === 1" x-cloak>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">عنوان <span class="text-red-500">*</span></label>
                                <input type="text" name="title" x-model="formData.title" @input="validateField('title', $event.target.value)"
                                       class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                       :class="{'border-red-500': errors.title, 'border-gray-300': !errors.title}"
                                       placeholder="مثال: پژو ۲۰۶ تیپ ۲">
                                <span class="text-red-500 text-xs mt-1" x-show="errors.title" x-text="errors.title"></span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">برند <span class="text-red-500">*</span></label>
                                <input type="text" name="brand" x-model="formData.brand" @input="validateField('brand', $event.target.value)"
                                       class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                       :class="{'border-red-500': errors.brand, 'border-gray-300': !errors.brand}"
                                       placeholder="مثال: پژو">
                                <span class="text-red-500 text-xs mt-1" x-show="errors.brand" x-text="errors.brand"></span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">مدل <span class="text-red-500">*</span></label>
                                <input type="text" name="model" x-model="formData.model" @input="validateField('model', $event.target.value)"
                                       class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                       :class="{'border-red-500': errors.model, 'border-gray-300': !errors.model}"
                                       placeholder="مثال: ۲۰۶">
                                <span class="text-red-500 text-xs mt-1" x-show="errors.model" x-text="errors.model"></span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">سال ساخت <span class="text-red-500">*</span></label>
                                <input type="number" name="year" x-model="formData.year" @input="validateField('year', $event.target.value)"
                                       class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                       :class="{'border-red-500': errors.year, 'border-gray-300': !errors.year}"
                                       placeholder="مثال: 1400" min="1300" max="1405">
                                <span class="text-red-500 text-xs mt-1" x-show="errors.year" x-text="errors.year"></span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">کارکرد (کیلومتر) <span class="text-red-500">*</span></label>
                                <input type="number" name="kilometers" x-model="formData.kilometers" @input="validateField('kilometers', $event.target.value)"
                                       class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                       :class="{'border-red-500': errors.kilometers, 'border-gray-300': !errors.kilometers}"
                                       placeholder="مثال: 45000" min="0">
                                <span class="text-red-500 text-xs mt-1" x-show="errors.kilometers" x-text="errors.kilometers"></span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">رنگ <span class="text-red-500">*</span></label>
                                <input type="text" name="color" x-model="formData.color" @input="validateField('color', $event.target.value)"
                                       class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                       :class="{'border-red-500': errors.color, 'border-gray-300': !errors.color}"
                                       placeholder="مثال: سفید">
                                <span class="text-red-500 text-xs mt-1" x-show="errors.color" x-text="errors.color"></span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ استعلام <span class="text-red-500">*</span></label>
                                <input type="text" name="inquiry_date" id="inquiry_date" x-model="formData.inquiry_date" @change="validateField('inquiry_date', $event.target.value)"
                                       class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                       :class="{'border-red-500': errors.inquiry_date, 'border-gray-300': !errors.inquiry_date}"
                                       placeholder="مثال: 1402/05/15" autocomplete="off">
                                <span class="text-red-500 text-xs mt-1" x-show="errors.inquiry_date" x-text="errors.inquiry_date"></span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">نام نمایشگاه</label>
                                <input type="text" name="showroom_name" x-model="formData.showroom_name"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                       placeholder="اختیاری">
                            </div>
                        </div>
                    </div>

                    <!-- صفحه دوم: مشخصات فنی و تکمیلی (اجباری) -->
                    <div x-show="currentStep === 2" x-cloak>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-searchable-select
                                    name="transmission"
                                    label="گیربکس"
                                    :options="[
                                        ['id' => 'دنده‌ای', 'text' => 'دنده‌ای'],
                                        ['id' => 'اتوماتیک', 'text' => 'اتوماتیک']
                                    ]"
                                    :selected="old('transmission')"
                                    placeholder="انتخاب گیربکس"
                                    :required="true"
                                />
                            </div>

                            <div>
                                <x-searchable-select
                                    name="fuel_type"
                                    label="نوع سوخت"
                                    :options="[
                                        ['id' => 'بنزین', 'text' => 'بنزین'],
                                        ['id' => 'گازوئیل', 'text' => 'گازوئیل'],
                                        ['id' => 'هیبرید', 'text' => 'هیبرید'],
                                        ['id' => 'برقی', 'text' => 'برقی']
                                    ]"
                                    :selected="old('fuel_type')"
                                    placeholder="انتخاب نوع سوخت"
                                    :required="true"
                                />
                            </div>

                            <div>
                                <x-searchable-select
                                    name="document_status"
                                    label="وضعیت سند"
                                    :options="[
                                        ['id' => 'seller_name', 'text' => 'به نام فروشنده'],
                                        ['id' => 'other_person', 'text' => 'به نام شخص دیگر'],
                                        ['id' => 'power_of_attorney', 'text' => 'وکالتی'],
                                        ['id' => 'in_mortgage', 'text' => 'در رهن']
                                    ]"
                                    :selected="old('document_status')"
                                    placeholder="انتخاب وضعیت سند"
                                    :required="true"
                                />
                            </div>

                            <div>
                                <x-searchable-select
                                    name="storage_location"
                                    label="محل نگهداری خودرو"
                                    :options="[
                                        ['id' => 'exhibition', 'text' => 'نمایشگاه'],
                                        ['id' => 'parking', 'text' => 'پارکینگ'],
                                        ['id' => 'seller_home', 'text' => 'منزل فروشنده'],
                                        ['id' => 'city', 'text' => 'شهرستان']
                                    ]"
                                    :selected="old('storage_location')"
                                    placeholder="انتخاب محل نگهداری"
                                    :required="true"
                                />
                            </div>
                            
                            <div>
                                <x-searchable-select
                                    name="purchase_priority"
                                    label="اولویت خرید"
                                    :options="[
                                        ['id' => 'high', 'text' => 'زیاد'],
                                        ['id' => 'medium', 'text' => 'متوسط'],
                                        ['id' => 'low', 'text' => 'کم']
                                    ]"
                                    :selected="old('purchase_priority')"
                                    placeholder="انتخاب اولویت"
                                    :required="true"
                                />
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">شماره تلفن <span class="text-red-500">*</span></label>
                                <input type="text" name="phone_number" x-model="formData.phone_number" @input="validateField('phone_number', $event.target.value)"
                                       class="w-full px-4 py-3 border rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                       :class="{'border-red-500': errors.phone_number, 'border-gray-300': !errors.phone_number}"
                                       placeholder="مثال: 09123456789">
                                <span class="text-red-500 text-xs mt-1" x-show="errors.phone_number" x-text="errors.phone_number"></span>
                            </div>
                            
                            <div>
                                <x-price-input 
                                    name="holding_price"
                                    label="قیمت اعلامی به هلدینگ (ریال)"
                                    :value="old('holding_price')"
                                    placeholder="مثال: ۵۰۰,۰۰۰,۰۰۰"
                                    :min="0"
                                    :required="true"
                                    currency="ریال"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- صفحه سوم: وضعیت و قیمت‌ها (اختیاری) -->
                    <div x-show="currentStep === 3" x-cloak>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                            <p class="text-yellow-800 text-sm">⚠️ این صفحه اختیاری است - می‌توانید بدون پر کردن این موارد ادامه دهید</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-price-input 
                                    name="purchase_price"
                                    label="قیمت خرید (ریال)"
                                    :value="old('purchase_price')"
                                    placeholder="مثال: ۴۵۰,۰۰۰,۰۰۰"
                                    :min="0"
                                    :required="false"
                                    currency="ریال"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تاریخ خرید</label>
                                <input type="text" name="purchase_date" id="purchase_date" x-model="formData.purchase_date"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                       placeholder="مثال: 1402/05/15" autocomplete="off">
                            </div>

                            <div>
                                <x-searchable-select
                                    name="body_condition"
                                    label="وضعیت بدنه"
                                    :options="[
                                        ['id' => 'colorless', 'text' => 'بی‌رنگ'],
                                        ['id' => 'two_tone', 'text' => 'دورنگ'],
                                        ['id' => 'fender_painted', 'text' => 'گلگیررنگ'],
                                        ['id' => 'full_painted', 'text' => 'سرتاپا رنگ'],
                                        ['id' => 'minor_scratch', 'text' => 'خط و خش جزئی'],
                                        ['id' => 'replaced', 'text' => 'تعویضی']
                                    ]"
                                    :selected="old('body_condition')"
                                    placeholder="انتخاب وضعیت بدنه"
                                    :required="false"
                                />
                            </div>

                            <div>
                                <x-searchable-select
                                    name="technical_condition"
                                    label="وضعیت فنی"
                                    :options="[
                                        ['id' => 'healthy', 'text' => 'سالم'],
                                        ['id' => 'needs_service', 'text' => 'نیاز به سرویس'],
                                        ['id' => 'has_noise', 'text' => 'صدا دارد'],
                                        ['id' => 'major_repair', 'text' => 'تعمیر اساسی شده']
                                    ]"
                                    :selected="old('technical_condition')"
                                    placeholder="انتخاب وضعیت فنی"
                                    :required="false"
                                />
                            </div>

                            <div>
                                <x-searchable-select
                                    name="owner_type"
                                    label="مالک خودرو"
                                    :options="[
                                        ['id' => 'personal', 'text' => 'شخصی'],
                                        ['id' => 'exhibition', 'text' => 'نمایشگاه'],
                                        ['id' => 'consignment', 'text' => 'امانی']
                                    ]"
                                    :selected="old('owner_type')"
                                    placeholder="انتخاب مالک"
                                    :required="false"
                                />
                            </div>

                            <div>
                                <x-price-input 
                                    name="market_price"
                                    label="قیمت بازار (ریال)"
                                    :value="old('market_price')"
                                    placeholder="مثال: ۵۵۰,۰۰۰,۰۰۰"
                                    :min="0"
                                    :required="false"
                                    currency="ریال"
                                />
                            </div>

                            <div>
                                <x-price-input 
                                    name="min_price"
                                    label="حد پایین (ریال)"
                                    :value="old('min_price')"
                                    placeholder="مثال: ۵۲۰,۰۰۰,۰۰۰"
                                    :min="0"
                                    :required="false"
                                    currency="ریال"
                                />
                            </div>

                            <div>
                                <x-searchable-select
                                    name="customer_type"
                                    label="نوع مشتری"
                                    :options="[
                                        ['id' => 'consumer', 'text' => 'مصرف‌کننده'],
                                        ['id' => 'business', 'text' => 'کاسب'],
                                        ['id' => 'both', 'text' => 'هردو']
                                    ]"
                                    :selected="old('customer_type')"
                                    placeholder="انتخاب نوع مشتری"
                                    :required="false"
                                />
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">توضیحات</label>
                                <textarea name="description" x-model="formData.description" rows="4" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                          placeholder="توضیحات اضافی درباره خودرو..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- صفحه چهارم: تصاویر و لینک آگهی (اختیاری) -->
                    <div x-show="currentStep === 4" x-cloak>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                            <p class="text-yellow-800 text-sm">⚠️ این صفحه اختیاری است - می‌توانید بدون پر کردن این موارد ثبت نهایی را انجام دهید</p>
                        </div>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">لینک آگهی</label>
                                <input type="url" name="listing_url" x-model="formData.listing_url"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition" 
                                       placeholder="https://divar.ir/...">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">تصاویر خودرو</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-blue-500 transition" id="dropzone">
                                    <input type="file" name="images[]" id="images" multiple accept="image/*" class="hidden" @change="previewImages($event)">
                                    <div class="cursor-pointer" onclick="document.getElementById('images').click()">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="mt-2 text-sm text-gray-600">
                                            برای آپلود کلیک کنید یا فایل‌ها را اینجا بکشید
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            فرمت‌های مجاز: JPEG, PNG, JPG, GIF (حداکثر ۲ مگابایت هر عکس)
                                        </p>
                                    </div>
                                </div>
                                
                                <div id="preview-container" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4"></div>
                                
                                @error('images') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                @error('images.*') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- دکمه‌های نویگیشن -->
                    <div class="flex justify-between items-center mt-8 pt-4 border-t">
                        <button type="button" 
                                x-show="currentStep > 1" 
                                @click="prevStep()"
                                class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition transform hover:scale-105">
                            ← قبلی
                        </button>
                        
                        <div class="flex gap-2">
                            <a href="{{ route('cars.index') }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-700 text-white font-bold rounded-xl transition">
                                انصراف
                            </a>
                            
                            <button type="button" 
                                    x-show="currentStep < 4" 
                                    @click="nextStep()"
                                    class="px-6 py-3 bg-blue-500 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transition transform hover:scale-105">
                                بعدی →
                            </button>
                            
                            <button type="submit" 
                                    x-show="currentStep === 4"
                                    class="px-6 py-3 bg-green-500 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg transition transform hover:scale-105">
                                ثبت خودرو
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function wizardForm() {
        return {
            currentStep: 1,
            steps: [
                { id: 1, name: 'اطلاعات پایه', completed: false, fields: ['title', 'brand', 'model', 'year', 'kilometers', 'color', 'inquiry_date'] },
                { id: 2, name: 'مشخصات فنی', completed: false, fields: ['transmission', 'fuel_type', 'phone_number', 'document_status', 'storage_location', 'holding_price', 'purchase_priority'] },
                { id: 3, name: 'وضعیت و قیمت', completed: false, fields: [] },
                { id: 4, name: 'تصاویر و آگهی', completed: false, fields: [] }
            ],
            formData: {
                title: '{{ old("title") }}',
                brand: '{{ old("brand") }}',
                model: '{{ old("model") }}',
                year: '{{ old("year") }}',
                kilometers: '{{ old("kilometers") }}',
                color: '{{ old("color") }}',
                purchase_price: '{{ old("purchase_price") }}',
                purchase_date: null,
                inquiry_date: '{{ old("inquiry_date", $todayJalali ?? "") }}',
                showroom_name: '{{ old("showroom_name") }}',
                transmission: '{{ old("transmission") }}',
                fuel_type: '{{ old("fuel_type") }}',
                phone_number: '{{ old("phone_number") }}',
                document_status: '{{ old("document_status") }}',
                storage_location: '{{ old("storage_location") }}',
                holding_price: '{{ old("holding_price") }}',
                purchase_priority: '{{ old("purchase_priority") }}',
                body_condition: '{{ old("body_condition") }}',
                technical_condition: '{{ old("technical_condition") }}',
                owner_type: '{{ old("owner_type") }}',
                market_price: '{{ old("market_price") }}',
                min_price: '{{ old("min_price") }}',
                customer_type: '{{ old("customer_type") }}',
                description: '{{ old("description") }}',
                listing_url: '{{ old("listing_url") }}'
            },
            errors: {},
            
            init() {
                this.initDatepickers();
                this.updateStepCompletion();
            },
            
            initDatepickers() {
                $('#inquiry_date').persianDatepicker({
                    format: 'YYYY/MM/DD',
                    autoClose: true,
                    initialValue: true,
                    calendar: { persian: true },
                    onSelect: (date) => {
                        this.formData.inquiry_date = date;
                        this.validateField('inquiry_date', date);
                    }
                });
                
                $('#purchase_date').persianDatepicker({
                    format: 'YYYY/MM/DD',
                    autoClose: true,
                    initialValue: false,
                    calendar: { persian: true },
                    onSelect: (date) => {
                        this.formData.purchase_date = date;
                    }
                });
            },
            
            updateStepCompletion() {
                this.steps[0].completed = this.isStep1Valid();
                this.steps[1].completed = this.isStep2Valid();
                this.steps[2].completed = true;
                this.steps[3].completed = true;
            },
            
            isStep1Valid() {
                const step1Fields = ['title', 'brand', 'model', 'year', 'kilometers', 'color', 'inquiry_date'];
                for (let field of step1Fields) {
                    const value = this.formData[field];
                    if (!value || value === '') {
                        return false;
                    }
                }
                return true;
            },
            
            isStep2Valid() {
                const step2Fields = ['transmission', 'fuel_type', 'phone_number', 'document_status', 'storage_location', 'holding_price', 'purchase_priority'];
                for (let field of step2Fields) {
                    const value = this.formData[field];
                    if (!value || value === '') {
                        return false;
                    }
                }
                if (this.formData.phone_number && !/^09[0-9]{9}$/.test(this.formData.phone_number)) {
                    return false;
                }
                return true;
            },
            
            validateField(field, value) {
                switch(field) {
                    case 'title':
                    case 'brand':
                    case 'model':
                    case 'color':
                        if (!value || value.trim() === '') {
                            this.errors[field] = 'این فیلد الزامی است';
                        } else {
                            delete this.errors[field];
                        }
                        break;
                    case 'year':
                        if (!value) {
                            this.errors.year = 'این فیلد الزامی است';
                        } else if (value < 1300 || value > 1405) {
                            this.errors.year = 'سال ساخت باید بین 1300 تا 1405 باشد';
                        } else {
                            delete this.errors.year;
                        }
                        break;
                    case 'kilometers':
                        if (!value && value !== 0) {
                            this.errors.kilometers = 'این فیلد الزامی است';
                        } else if (value < 0) {
                            this.errors.kilometers = 'کارکرد نمی‌تواند منفی باشد';
                        } else {
                            delete this.errors.kilometers;
                        }
                        break;
                    case 'holding_price':
                        if (!value && value !== 0) {
                            this.errors.holding_price = 'این فیلد الزامی است';
                        } else if (value < 0) {
                            this.errors.holding_price = 'قیمت نمی‌تواند منفی باشد';
                        } else {
                            delete this.errors.holding_price;
                        }
                        break;
                    case 'inquiry_date':
                        if (!value) {
                            this.errors.inquiry_date = 'این فیلد الزامی است';
                        } else {
                            delete this.errors.inquiry_date;
                        }
                        break;
                    case 'phone_number':
                        if (!value) {
                            this.errors.phone_number = 'این فیلد الزامی است';
                        } else if (!/^09[0-9]{9}$/.test(value)) {
                            this.errors.phone_number = 'شماره تلفن باید با 09 شروع شود و 11 رقم باشد';
                        } else {
                            delete this.errors.phone_number;
                        }
                        break;
                    default:
                        if (!value || value === '') {
                            this.errors[field] = 'این فیلد الزامی است';
                        } else {
                            delete this.errors[field];
                        }
                }
                
                this.updateStepCompletion();
            },
            
            nextStep() {
                if (this.currentStep < 4) {
                    this.currentStep++;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            },
            
            prevStep() {
                if (this.currentStep > 1) {
                    this.currentStep--;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            },
            
            previewImages(event) {
                const input = event.target;
                const previewContainer = document.getElementById('preview-container');
                previewContainer.innerHTML = '';
                
                if (input.files) {
                    for (let i = 0; i < input.files.length; i++) {
                        const file = input.files[i];
                        
                        if (file.size > 2 * 1024 * 1024) {
                            alert(`فایل ${file.name} بزرگتر از ۲ مگابایت است`);
                            continue;
                        }
                        
                        const reader = new FileReader();
                        const previewDiv = document.createElement('div');
                        previewDiv.className = 'relative group';
                        
                        reader.onload = function(e) {
                            previewDiv.innerHTML = `
                                <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg border-2 border-gray-300">
                                <button type="button" onclick="removeImage(this)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            `;
                        }
                        
                        reader.readAsDataURL(file);
                        previewContainer.appendChild(previewDiv);
                    }
                }
            }
        }
    }
    
    function removeImage(button) {
        button.closest('.relative').remove();
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const dropzone = document.getElementById('dropzone');
        if (dropzone) {
            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.classList.add('border-blue-500', 'bg-blue-50');
            });
            
            dropzone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                dropzone.classList.remove('border-blue-500', 'bg-blue-50');
            });
            
            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.classList.remove('border-blue-500', 'bg-blue-50');
                
                const files = e.dataTransfer.files;
                const input = document.getElementById('images');
                input.files = files;
                
                const event = new Event('change');
                input.dispatchEvent(event);
            });
        }
    });
</script>
@endpush

<style>
    [x-cloak] { display: none !important; }
</style>