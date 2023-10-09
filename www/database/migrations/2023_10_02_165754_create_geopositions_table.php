<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('geopositions', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('geopositions');
    }
};
