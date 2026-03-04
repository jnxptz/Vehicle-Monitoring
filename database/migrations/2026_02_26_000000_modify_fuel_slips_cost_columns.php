<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fuel_slips', function (Blueprint $table) {
            // Drop the old 'cost' column if it exists
            if (Schema::hasColumn('fuel_slips', 'cost')) {
                $table->dropColumn('cost');
            }
            
            // Add new columns for unit_cost and total_cost
            $table->decimal('unit_cost', 10, 2)->after('liters');
            $table->decimal('total_cost', 10, 2)->after('unit_cost');
        });
    }

    public function down(): void
    {
        Schema::table('fuel_slips', function (Blueprint $table) {
            // Drop the new columns
            if (Schema::hasColumn('fuel_slips', 'unit_cost')) {
                $table->dropColumn('unit_cost');
            }
            if (Schema::hasColumn('fuel_slips', 'total_cost')) {
                $table->dropColumn('total_cost');
            }
            
            // Restore the old 'cost' column
            $table->decimal('cost', 10, 2)->after('liters');
        });
    }
};
