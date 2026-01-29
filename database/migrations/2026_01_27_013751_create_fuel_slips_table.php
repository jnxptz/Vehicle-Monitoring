<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_slips', function (Blueprint $table) {
            $table->id();

            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('cascade');

            $table->string('vehicle_name'); // manual input
            $table->string('plate_number'); // manual input

            $table->decimal('liters', 8, 2);
            $table->decimal('cost', 10, 2);
            $table->integer('km_reading');
            $table->string('driver');
            $table->string('control_number')->unique();
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_slips');
    }
};
