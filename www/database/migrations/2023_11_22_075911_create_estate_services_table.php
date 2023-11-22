<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('estate_service', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('estate_id');
            $table->integer('service_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_estate');
    }
};
