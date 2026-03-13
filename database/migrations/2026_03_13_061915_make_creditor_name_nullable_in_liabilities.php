<?php
// database/migrations/[timestamp]_make_creditor_name_nullable_in_liabilities.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ابتدا مقدار پیش‌فرض برای رکوردهای موجود
        DB::statement('UPDATE liabilities SET creditor_name = "طلبکار" WHERE creditor_name IS NULL');
        
        Schema::table('liabilities', function (Blueprint $table) {
            $table->string('creditor_name')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('liabilities', function (Blueprint $table) {
            $table->string('creditor_name')->nullable(false)->change();
        });
    }
};