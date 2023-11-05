<?php

namespace Domain\Estate\Actions;

use Domain\Estate\DataTransferObjects\EstateFiltersData;
use Domain\Shared\Models\Actor\User;

class SaveUserFiltersAction
{
    public static function execute(EstateFiltersData $data): void
    {
        User::updateOrCreate(
            [
                'id' => $data->user->id
            ],
            [
                ...$data->user->all(),
                'filters' => (string)json_encode($data->except('user')->all())
            ]
        );
    }
}
