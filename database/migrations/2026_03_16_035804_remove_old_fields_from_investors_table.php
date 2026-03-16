<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('investors', function (Blueprint $table) {
            // حذف فیلدهای قدیمی
            $table->dropColumn(['full_name', 'national_code', 'phone', 'email', 'address']);
        });
    }

    public function down()
    {
        Schema::table('investors', function (Blueprint $table) {
            // برگردوندن فیلدها در صورت نیاز
            $table->string('full_name')->nullable();
            $table->string('national_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
        });
    }
};