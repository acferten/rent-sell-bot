<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('estates', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
            $table->foreign('type_id')->references('id')->on('types')->onDelete('cascade');;
        });

        Schema::table('prices', function (Blueprint $table) {
            $table->foreign('estate_id')->references('id')->on('estates')->onDelete('cascade');;
        });

        Schema::table('photos', function (Blueprint $table) {
            $table->foreign('estate_id')->references('id')->on('estates')->onDelete('cascade');;
        });

        Schema::table('amenity_estate', function (Blueprint $table) {
            $table->foreign('estate_id')->references('id')->on('estates')->onDelete('cascade');
            $table->foreign('amenity_id')->references('id')->on('amenities')->onDelete('cascade');;
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->foreign('estate_id')->references('id')->on('estates')->onDelete('cascade');;
        });
    }

    public function down(): void
    {
        Schema::table('', function (Blueprint $table) {
            //
        });
    }
};
