<?php

namespace Domain\Estate\DataTransferObjects;

use Domain\Estate\Models\EstateInclude;
use Spatie\LaravelData\Data;

class EstateIncludeData extends Data
{
    public function __construct(
        public readonly int    $id,
        public readonly string $title,
    )
    {
    }

    public static function fromModel(EstateInclude $model): EstateIncludeData
    {
        return new EstateIncludeData(
            id: $model->id,
            title: $model->title,
        );
    }
}
