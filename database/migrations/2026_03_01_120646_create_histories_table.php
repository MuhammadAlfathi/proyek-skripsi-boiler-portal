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
        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->integer('fuel_start')->nullable();
            $table->integer('fuel_end')->nullable();
            $table->integer('fuel_additional')->nullable();
            $table->integer('fuel_resupply')->nullable();
            $table->integer('fuel_consumption')
                  ->storedAs('fuel_start + COALESCE(fuel_additional, 0) - fuel_end');
            $table->time('cssd_start')->nullable();
            $table->time('cssd_end')->nullable();
            $table->time('laundry_start')->nullable();
            $table->time('laundry_end')->nullable();
            $table->time('hour_start')->nullable();
            $table->time('hour_end')->nullable();
            $table->integer('hour_duration')
                  ->storedAs('GREATEST(TIME_TO_SEC(hour_end) - TIME_TO_SEC(hour_start), 0)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
