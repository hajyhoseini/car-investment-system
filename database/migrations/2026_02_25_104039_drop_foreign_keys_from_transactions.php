<?php
// database/migrations/[timestamp]_drop_foreign_keys_from_transactions.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // حذف کلیدهای خارجی مرتبط با accounts
            $table->dropForeign(['from_account_id']);
            $table->dropForeign(['to_account_id']);
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // در صورت بازگشت، دوباره کلیدها رو اضافه کن
            $table->foreign('from_account_id')->references('id')->on('accounts')->nullOnDelete();
            $table->foreign('to_account_id')->references('id')->on('accounts')->nullOnDelete();
        });
    }
};