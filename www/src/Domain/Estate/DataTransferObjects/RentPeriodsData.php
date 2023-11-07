<?php

namespace Domain\Estate\DataTransferObjects;

use Domain\Estate\Enums\EstatePeriods;
use Spatie\LaravelData\Data;

class RentPeriodsData extends Data
{
    public function __construct(
        public readonly int           $price,
        public readonly EstatePeriods $period
    )
    {
    }
}
