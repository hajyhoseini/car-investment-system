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
            // بررسی کن که ستون person_id وجود نداره
            if (!Schema::hasColumn('liabilities', 'person_id')) {
                $table->foreignId('person_id')->nullable()->after('type')->constrained()->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('liabilities', function (Blueprint $table) {
            if (Schema::hasColumn('liabilities', 'person_id')) {
                $table->dropForeign(['person_id']);
                $table->dropColumn('person_id');
            }
        });
    }
};