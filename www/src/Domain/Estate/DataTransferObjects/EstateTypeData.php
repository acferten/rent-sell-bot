<?php

namespace Domain\Estate\DataTransferObjects;

use Domain\Estate\Models\EstateType;
use Spatie\LaravelData\Data;

class EstateTypeData extends Data
{
    public function __construct(
        public readonly int    $id,
        public readonly string $title,
    )
    {
    }

    /**
     * Return id and name of specialty
     */
    public static function fromModel(EstateType $model): EstateTypeData
    {
        return new EstateTypeData(
            id: $model->id,
            title: $model->title,
        );
    }
}
