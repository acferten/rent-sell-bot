<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('estates', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
            $table->foreign('house_type_id')->references('id')->on('house_types')->onDelete('cascade');;
        });

        Schema::table('estate_prices', function (Blueprint $table) {
            $table->foreign('estate_id')->references('id')->on('estates')->onDelete('cascade');;
        });

        Schema::table('estate_photos', function (Blueprint $table) {
            $table->foreign('estate_id')->references('id')->on('estates')->onDelete('cascade');;
        });

        Schema::table('estate_includes', function (Blueprint $table) {
            $table->foreign('estate_id')->references('id')->on('estates')->onDelete('cascade');
            $table->foreign('include_id')->references('id')->on('includes')->onDelete('cascade');;
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
