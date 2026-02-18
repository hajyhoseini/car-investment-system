<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['bank', 'dollar', 'gold']); // نوع دارایی
            $table->string('name'); // نام (مثلاً بانک ملی، سکه طلا)
            $table->decimal('amount', 15, 2); // مقدار
            $table->decimal('value', 15, 2)->nullable(); // ارزش به ریال
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};