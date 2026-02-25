<?php
// database/migrations/[timestamp]_add_person_id_to_liabilities_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('liabilities', function (Blueprint $table) {
            $table->foreignId('person_id')->nullable()->constrained()->nullOnDelete();
            // creditor_name رو می‌تونیم نگه داریم یا حذف کنیم
        });
    }

    public function down(): void
    {
        Schema::table('liabilities', function (Blueprint $table) {
            $table->dropForeign(['person_id']);
            $table->dropColumn('person_id');
        });
    }
};