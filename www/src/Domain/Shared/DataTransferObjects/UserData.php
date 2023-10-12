<?php

namespace Domain\Shared\DataTransferObjects;

use Spatie\LaravelData\Data;

class UserData extends Data
{
    public function __construct(
        public                 $id,
        public readonly string $username,
        public readonly string $first_name,
        public readonly string $last_name
    )
    {
    }
}
