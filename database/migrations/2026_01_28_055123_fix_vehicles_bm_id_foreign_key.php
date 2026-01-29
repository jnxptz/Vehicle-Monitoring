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
        Schema::table('vehicles', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['bm_id']);
            
            // Add new foreign key constraint referencing users table
            $table->foreign('bm_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            // Drop the users foreign key
            $table->dropForeign(['bm_id']);
            
            // Restore the original bms foreign key
            $table->foreign('bm_id')->references('id')->on('bms')->onDelete('cascade');
        });
    }
};
