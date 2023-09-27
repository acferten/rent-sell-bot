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
        Schema::create('estate_prices', function (Blueprint $table) {
            $table->id();
            $table->integer('price')->comment('Цена');
            $table->integer('period_id')->comment('ID периода');
            $table->integer('estate_id')->comment('ID объекта недвижимости');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estate_prices');
    }
};
