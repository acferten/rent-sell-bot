<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('estates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('deal_type');
            $table->integer('geoposition_id');
            $table->string('video_review', 100)->nullable();
            $table->integer('bedrooms');
            $table->enum('status', ['Активно', 'Закрыто', 'Заблокировано', 'На осмотре'])
                ->default('Активно');
            $table->integer('bathrooms');
            $table->integer('house_type_id');
            $table->integer('conditioners');
            $table->string('description', 1000);
            $table->integer('views');
            $table->integer('chattings');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estates');
    }
};
