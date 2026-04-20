<?php
// database/migrations/xxxx_xx_xx_add_new_fields_to_cars_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cars', function (Blueprint $table) {
            // فیلدهای جدید
            $table->date('inquiry_date')->nullable()->after('purchase_date');
            $table->string('showroom_name')->nullable()->after('inquiry_date');
            $table->string('phone_number', 20)->nullable()->after('showroom_name');
            $table->string('body_condition')->nullable()->after('phone_number');
            $table->string('technical_condition')->nullable()->after('body_condition');
            $table->string('document_status')->nullable()->after('technical_condition');
            $table->string('storage_location')->nullable()->after('document_status');
            $table->string('owner_type')->nullable()->after('storage_location');
            $table->decimal('market_price', 15, 2)->nullable()->after('owner_type');
            $table->decimal('holding_price', 15, 2)->nullable()->after('market_price');
            $table->decimal('min_price', 15, 2)->nullable()->after('holding_price');
            $table->string('customer_type')->nullable()->after('min_price');
            $table->string('purchase_priority')->default('medium')->after('customer_type');
            $table->text('listing_url')->nullable()->after('purchase_priority');
            
            // ایندکس‌ها برای جستجوی بهتر
            $table->index('body_condition');
            $table->index('technical_condition');
            $table->index('document_status');
            $table->index('storage_location');
            $table->index('owner_type');
            $table->index('customer_type');
            $table->index('purchase_priority');
            $table->index('market_price');
        });
    }

    public function down()
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn([
                'inquiry_date',
                'showroom_name',
                'phone_number',
                'body_condition',
                'technical_condition',
                'document_status',
                'storage_location',
                'owner_type',
                'market_price',
                'holding_price',
                'min_price',
                'customer_type',
                'purchase_priority',
                'listing_url'
            ]);
        });
    }
};