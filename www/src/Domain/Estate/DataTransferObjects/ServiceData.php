<?php

namespace Domain\Estate\DataTransferObjects;

use Domain\Estate\Models\Amenity;
use Domain\Estate\Models\Service;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

class ServiceData extends Data
{
    public function __construct(
        public readonly int    $id,
        public readonly string $title,
    )
    {
    }

    public static function fromModel(Service $model): ServiceData
    {
        return new ServiceData(
            id: $model->id,
            title: $model->title,
        );
    }
}
