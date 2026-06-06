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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->integer('stock_before')->nullable();
            $table->integer('stock_additional')->nullable();
            $table->integer('stock_consumed')->nullable();
            $table->integer('stock_after')->storedAs('stock_before + COALESCE(stock_additional, 0) - stock_consumed');
            $table->integer('stock_calibration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};