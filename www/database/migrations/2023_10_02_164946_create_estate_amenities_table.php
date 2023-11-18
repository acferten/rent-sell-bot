<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('amenity_estate', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('estate_id');
            $table->integer('amenity_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amenity_estate');
    }
};
