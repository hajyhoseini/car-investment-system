<?php
// database/migrations/[timestamp]_rename_account_fields_in_transactions.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('from_account_id', 'from_asset_id');
            $table->renameColumn('to_account_id', 'to_asset_id');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->renameColumn('from_asset_id', 'from_account_id');
            $table->renameColumn('to_asset_id', 'to_account_id');
        });
    }
};