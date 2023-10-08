<?php

namespace Domain\Estate\DataTransferObjects;

use Domain\Estate\Enums\EstatePeriods;
use Domain\Estate\Models\EstatePrice;
use Spatie\LaravelData\Data;

class EstatePriceData extends Data
{
    public function __construct(
        public readonly int           $id,
        public readonly int           $price,
        public readonly EstatePeriods $period
    )
    {
    }

    public static function fromModel(EstatePrice $model): EstatePriceData
    {
        return new self(
            id: $model->id,
            price: $model->price,
            period: $model->period()
        );
    }
}
