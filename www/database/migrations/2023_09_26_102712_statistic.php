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
        Schema::create('statistic', function (Blueprint $table) {
            $table->id();
            $table->date('date')->comment('Дата статистики');
            $table->integer('starts')->comment('Количество запусков приложения');
            $table->integer('new_ads')->comment('Количество новых объявлений');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistic');
    }
};
