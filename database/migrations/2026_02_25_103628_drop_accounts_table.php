<?php
// database/migrations/[timestamp]_drop_accounts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // غیرفعال کردن موقت چک کلید خارجی
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        Schema::dropIfExists('accounts');
        
        // فعال کردن مجدد چک کلید خارجی
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['bank', 'cash', 'wallet'])->default('bank');
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('card_number')->nullable();
            $table->string('sheba_number')->nullable();
            $table->decimal('balance', 15, 2)->default(0);
            $table->string('currency')->default('IRR');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};