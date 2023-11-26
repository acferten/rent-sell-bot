<?php

namespace Domain\Estate\Events;

use Domain\Estate\Models\Estate;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EstateUpdatedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Estate $estate,
    ) {}
}
