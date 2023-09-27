<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('estates', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('estate_type_id');
            $table->integer('deal_type_id');
            $table->integer('geoposition_id');
            $table->string('video_review', 100)->comment('Путь к видео в хранилище');
            $table->integer('bedrooms')->comment('Количество спален');
            $table->integer('bathrooms')->comment('Количество ванных комнат');
            $table->integer('conditioners')->comment('Количество кондиционеров');
            $table->boolean('pool')->comment('Наличие бассейна');
            $table->string('description', 1000)->comment('Дополнительная информация');
            $table->integer('status_id');
            $table->integer('views')->comment('Количество просмотров объявления');
            $table->integer('chattings')->comment('Количество чатов с арендодателем');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estates');
    }
};
