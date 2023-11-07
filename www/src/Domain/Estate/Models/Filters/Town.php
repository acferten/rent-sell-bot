<?php

namespace Domain\Estate\Models\Filters;

use Lacodix\LaravelModelFilter\Enums\FilterMode;
use Lacodix\LaravelModelFilter\Filters\StringFilter;

class Town extends StringFilter
{
    public FilterMode $mode = FilterMode::EQUAL;

    protected string $field = 'town';
}
