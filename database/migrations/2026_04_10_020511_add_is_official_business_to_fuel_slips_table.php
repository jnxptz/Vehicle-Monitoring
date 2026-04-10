<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('fuel_slips', function (Blueprint $table) {
            $table->boolean('is_official_business')->default(false)->after('approved_by_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fuel_slips', function (Blueprint $table) {
            $table->dropColumn('is_official_business');
        });
    }
};
