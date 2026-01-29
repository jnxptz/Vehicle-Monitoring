<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('maintenances')) {
            Schema::create('maintenances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
                $table->text('operation');
                $table->decimal('cost', 10, 2);
                $table->string('conduct');
                $table->string('call_of_no')->unique();
                $table->date('date');
                $table->string('photo')->nullable();
                $table->timestamps();
            });

            return;
        }

        // If the table already exists (e.g., created manually), ensure required columns exist.
        Schema::table('maintenances', function (Blueprint $table) {
            if (!Schema::hasColumn('maintenances', 'vehicle_id')) {
                $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('maintenances', 'operation')) {
                $table->text('operation')->nullable();
            }
            if (!Schema::hasColumn('maintenances', 'cost')) {
                $table->decimal('cost', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('maintenances', 'conduct')) {
                $table->string('conduct')->nullable();
            }
            if (!Schema::hasColumn('maintenances', 'call_of_no')) {
                $table->string('call_of_no')->nullable()->unique();
            }
            if (!Schema::hasColumn('maintenances', 'date')) {
                $table->date('date')->nullable();
            }
            if (!Schema::hasColumn('maintenances', 'photo')) {
                $table->string('photo')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};

