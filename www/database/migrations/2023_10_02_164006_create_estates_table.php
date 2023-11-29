<?php

use Domain\Estate\Enums\BaliDistricts;
use Domain\Estate\Enums\EstateStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('estates', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();

            $table->enum('status', EstateStatus::values())
                ->default('Не заполнен');
            $table->string('title');
            $table->date('available_date');
            $table->string('deal_type');
            $table->integer('bedrooms');
            $table->integer('bathrooms');
            $table->integer('conditioners');
            $table->string('main_photo');
            $table->string('check_photo')->nullable();
            $table->string('video')->nullable();
            $table->text('description');

            $table->float('latitude', 12, 9)->nullable();
            $table->float('longitude', 12, 9)->nullable();

            $table->enum('custom_district', BaliDistricts::values());
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('county')->nullable();
            $table->string('town')->nullable();
            $table->string('district')->nullable();
            $table->string('street')->nullable();
            $table->string('house_number')->nullable();

            $table->integer('price')->nullable();
            $table->enum('paid_with', ['Bank BRI', 'Tinkoff'])->nullable();
            $table->integer('views')->default(0);
            $table->integer('chattings')->default(0);
            $table->date('end_date')->nullable();
            $table->date('relevance_date')->default(now());

            $table->integer('type_id');
            $table->bigInteger('user_id');
            $table->bigInteger('admin_message_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estates');
    }
};
