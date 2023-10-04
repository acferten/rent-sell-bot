<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('estate_includes', function (Blueprint $table) {
            $table->id();
            $table->integer('estate_id');
            $table->integer('include_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estate_includes');
    }
};
