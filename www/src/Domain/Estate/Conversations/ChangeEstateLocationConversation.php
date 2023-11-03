<?php

namespace Domain\Estate\Conversations;

use Domain\Estate\Actions\SendPreviewMessageAction;
use Domain\Estate\Models\Estate;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

class ChangeEstateLocationConversation extends Conversation
{
    protected ?string $step = 'change';

    public Estate $estate;

    public function change(Nutgram $bot): void
    {
        $this->estate = Estate::find(1)->first();

        $bot->sendMessage(
            text: "<b>Шаг 2 из 3</b>
Отправьте геолокацию вашего объекта. Для этого перейдите во вкладку прикрепить и отправьте геолокацию боту.",
            parse_mode: 'html'
        );

        $this->next('ChangeLocationStepTwo');
    }

    public function ChangeLocationStepTwo(Nutgram $bot): void
    {
        $location = $bot->message()->location;

        $this->estate->update([
            'latitude' => $location->latitude,
            'longitude' => $location->longitude
        ]);

        SendPreviewMessageAction::execute($bot, $this->estate->id);
    }
}
