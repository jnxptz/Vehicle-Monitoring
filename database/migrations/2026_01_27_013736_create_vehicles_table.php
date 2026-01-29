<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bm_id')->constrained()->onDelete('cascade');
            $table->string('plate_number')->unique();
            $table->decimal('monthly_fuel_limit', 8, 2)->default(100); // 100 liters per month
            $table->integer('current_km')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
