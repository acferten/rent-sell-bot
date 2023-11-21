<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('estate_id');
            $table->enum('reason', ['Не соответствует описанию', 'Неверная цена', 'Уже сняли', 'Владелец не отвечает', 'Владелец не вежливый']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
