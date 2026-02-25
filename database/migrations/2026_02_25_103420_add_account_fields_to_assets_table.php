<?php
// database/migrations/[timestamp]_add_account_fields_to_assets_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->after('name');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('card_number')->nullable()->after('account_number');
            $table->string('sheba_number')->nullable()->after('card_number');
            $table->boolean('is_active')->default(true)->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'account_number', 'card_number', 'sheba_number', 'is_active']);
        });
    }
};