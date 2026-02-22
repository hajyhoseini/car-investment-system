<?php
// database/migrations/[timestamp]_make_user_id_unique_in_investors.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // اول کاربرهای تکراری رو پاک می‌کنیم
        DB::statement('
            DELETE i1 FROM investors i1
            INNER JOIN investors i2 
            WHERE i1.id > i2.id 
            AND i1.user_id IS NOT NULL 
            AND i1.user_id = i2.user_id
        ');
        
        // بعد unique constraint اضافه می‌کنیم
        Schema::table('investors', function (Blueprint $table) {
            $table->unique('user_id', 'investors_user_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('investors', function (Blueprint $table) {
            $table->dropUnique('investors_user_id_unique');
        });
    }
};