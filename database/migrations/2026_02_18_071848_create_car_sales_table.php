<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->decimal('selling_price', 15, 2); // قیمت فروش
            $table->decimal('total_profit', 15, 2); // سود کل
            $table->date('sale_date'); // تاریخ فروش
            $table->string('buyer_name'); // نام خریدار
            $table->string('buyer_phone'); // تلفن خریدار
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_sales');
    }
};