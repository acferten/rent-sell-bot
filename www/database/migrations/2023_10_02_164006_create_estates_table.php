<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('estates', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->bigInteger('user_id');
            $table->string('deal_type');
            $table->string('video_review', 100)->nullable();
            $table->integer('bedrooms');
            $table->enum('status', ['Активно', 'Закрыто', 'Заблокировано', 'На осмотре', 'Ожидает подтверждения', 'Не заполнен'])
                ->default('Ожидает подтверждения');
            $table->integer('bathrooms');
            $table->integer('house_type_id');
            $table->integer('conditioners');
            $table->string('description', 1000);
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('county')->nullable();
            $table->string('town')->nullable();
            $table->string('district')->nullable();
            $table->string('street')->nullable();
            $table->string('house_number')->nullable();
            $table->integer('price')->nullable();
            $table->integer('views')->default(0);
            $table->integer('chattings')->default(0);
            $table->date('end_date')->nullable();
            $table->date('relevance_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estates');
    }
};
