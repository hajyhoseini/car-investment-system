<?php
// database/migrations/[timestamp]_create_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->enum('type', ['income', 'expense']); // دریافت یا پرداخت
            $table->decimal('amount', 15, 2);
            $table->foreignId('from_account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->foreignId('to_account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->foreignId('person_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained()->nullOnDelete();
            $table->string('check_number')->nullable();
            $table->date('check_date')->nullable();
            $table->date('transaction_date');
            $table->string('description')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            
            // لینک به دارایی‌ها (اختیاری)
            $table->foreignId('asset_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('asset_type', ['car', 'gold', 'dollar', 'other'])->nullable();
            
            // لینک به فروش یا سرمایه‌گذاری (اختیاری)
            $table->foreignId('car_sale_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('investment_id')->nullable()->constrained()->nullOnDelete();
            
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};