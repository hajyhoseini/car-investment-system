<?php
// database/migrations/[timestamp]_add_person_id_to_car_sales_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('car_sales', function (Blueprint $table) {
            $table->foreignId('person_id')->nullable()->constrained()->nullOnDelete();
            // buyer_name و buyer_phone رو می‌تونیم نگه داریم یا حذف کنیم
        });
    }

    public function down(): void
    {
        Schema::table('car_sales', function (Blueprint $table) {
            $table->dropForeign(['person_id']);
            $table->dropColumn('person_id');
        });
    }
};