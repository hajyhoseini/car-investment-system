<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // عنوان آگهی
            $table->string('brand'); // برند
            $table->string('model'); // مدل
            $table->integer('year'); // سال ساخت
            $table->integer('kilometers'); // کارکرد
            $table->string('fuel_type'); // نوع سوخت
            $table->string('transmission'); // گیربکس
            $table->string('color')->nullable(); // رنگ
            $table->text('description')->nullable(); // توضیحات
            $table->decimal('purchase_price', 15, 2); // قیمت خرید
            $table->date('purchase_date'); // تاریخ خرید
            $table->enum('status', ['available', 'sold', 'reserved'])->default('available'); // وضعیت
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};