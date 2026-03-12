<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            if (!Schema::hasColumn('maintenances', 'prepared_by_name')) {
                $table->string('prepared_by_name')->nullable()->after('date');
            }
            if (!Schema::hasColumn('maintenances', 'approved_by_name')) {
                $table->string('approved_by_name')->nullable()->after('prepared_by_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            if (Schema::hasColumn('maintenances', 'prepared_by_name')) {
                $table->dropColumn('prepared_by_name');
            }
            if (Schema::hasColumn('maintenances', 'approved_by_name')) {
                $table->dropColumn('approved_by_name');
            }
        });
    }
};
