<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deal_types', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->comment('Название типа сделки');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deal_types');
    }
};
