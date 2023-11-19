<?php

namespace Domain\Estate\DataTransferObjects;

use Domain\Estate\Models\Amenity;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

class AmenityData extends Data
{
    public function __construct(
        public readonly int    $id,
        public readonly string $title,
    )
    {
    }

    public static function fromModel(Amenity $model): AmenityData
    {
        return new AmenityData(
            id: $model->id,
            title: $model->title,
        );
    }
}
