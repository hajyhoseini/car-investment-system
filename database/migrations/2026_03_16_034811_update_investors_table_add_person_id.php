<?php
// database/migrations/xxxx_xx_xx_update_investors_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('investors', function (Blueprint $table) {
            // اضافه کردن person_id
            $table->foreignId('person_id')->nullable()->constrained()->nullOnDelete();
            
            // می‌تونیم فیلدهای قدیمی رو حذف کنیم یا نگه داریم
            // اگر می‌خواید حذف کنید:
            // $table->dropColumn(['full_name', 'national_code', 'phone', 'email', 'address']);
        });
    }

    public function down()
    {
        Schema::table('investors', function (Blueprint $table) {
            $table->dropForeign(['person_id']);
            $table->dropColumn('person_id');
        });
    }
};