<?php
// database/migrations/[timestamp]_create_receivables_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receivables', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 15, 2);
            $table->enum('currency_type', ['cash', 'gold', 'dollar', 'check', 'other'])->default('cash');
            $table->json('currency_details')->nullable(); // برای ذخیره جزئیات چک، طلا و...
            $table->date('receivable_date');
            $table->date('due_date')->nullable();
            $table->foreignId('person_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['pending', 'partially_paid', 'paid', 'overdue'])->default('pending');
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->decimal('remaining_amount', 15, 2)->default(0);
            $table->string('attachments')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receivables');
    }
};