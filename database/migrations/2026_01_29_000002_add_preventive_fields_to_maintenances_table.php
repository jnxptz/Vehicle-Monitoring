<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            if (!Schema::hasColumn('maintenances', 'maintenance_type')) {
                $table->string('maintenance_type')->default('preventive')->after('vehicle_id');
            }

            if (!Schema::hasColumn('maintenances', 'maintenance_km')) {
                $table->integer('maintenance_km')->nullable()->after('maintenance_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            if (Schema::hasColumn('maintenances', 'maintenance_km')) {
                $table->dropColumn('maintenance_km');
            }
            if (Schema::hasColumn('maintenances', 'maintenance_type')) {
                $table->dropColumn('maintenance_type');
            }
        });
    }
};

