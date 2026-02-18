<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('liabilities', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['debt', 'check', 'installment']); // نوع تعهد
            $table->string('creditor_name'); // نام طلبکار
            $table->decimal('amount', 15, 2); // مبلغ کل
            $table->decimal('remaining_amount', 15, 2); // مبلغ باقی‌مانده
            $table->date('due_date'); // تاریخ سررسید
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending'); // وضعیت
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liabilities');
    }
};