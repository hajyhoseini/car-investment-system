<?php
// database/migrations/2026_04_21_000000_make_car_fields_nullable.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cars', function (Blueprint $table) {
            // فیلدهای صفحه 3 (اختیاری)
            $table->decimal('purchase_price', 15, 2)->nullable()->change();
            $table->date('purchase_date')->nullable()->change();
            $table->string('body_condition')->nullable()->change();
            $table->string('technical_condition')->nullable()->change();
            $table->string('owner_type')->nullable()->change();
            $table->decimal('market_price', 15, 2)->nullable()->change();
            $table->decimal('min_price', 15, 2)->nullable()->change();
            $table->string('customer_type')->nullable()->change();
            
            // فیلدهای صفحه 4 (اختیاری)
            $table->text('listing_url')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('cars', function (Blueprint $table) {
            // برگردوندن به حالت قبل (NOT NULL)
            $table->decimal('purchase_price', 15, 2)->nullable(false)->change();
            $table->date('purchase_date')->nullable(false)->change();
            $table->string('body_condition')->nullable(false)->change();
            $table->string('technical_condition')->nullable(false)->change();
            $table->string('owner_type')->nullable(false)->change();
            $table->decimal('market_price', 15, 2)->nullable(false)->change();
            $table->decimal('min_price', 15, 2)->nullable(false)->change();
            $table->string('customer_type')->nullable(false)->change();
            $table->text('listing_url')->nullable(false)->change();
        });
    }
};