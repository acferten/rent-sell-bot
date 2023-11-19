<?php

namespace Domain\Estate\Actions;

use Domain\Estate\Conversations\GetFilteredEstatesConversation;
use Domain\Estate\DataTransferObjects\EstateFiltersData;
use Domain\Shared\Models\User;
use SergiX44\Nutgram\Nutgram;
use function Nutgram\Laravel\Support\webAppData;

class SaveUserFiltersAction
{
    public static function execute(EstateFiltersData $data)
    {
        $user = User::updateOrCreate(
            [
                'id' => $data->user->id
            ],
            [
                ...$data->user->all(),
                'filters' => (string)json_encode($data->except('user')->all())
            ]
        );

        return $user;
    }
}
