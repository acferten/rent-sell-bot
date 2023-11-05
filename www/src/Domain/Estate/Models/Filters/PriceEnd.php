<?php

namespace Domain\Estate\Models\Filters;

use Lacodix\LaravelModelFilter\Enums\FilterMode;
use Lacodix\LaravelModelFilter\Filters\NumericFilter;

class PriceEnd extends NumericFilter
{
    public FilterMode $mode = FilterMode::LOWER_OR_EQUAL;

    protected string $field = 'price';
}
