<?php

namespace Domain\Estate\Conversations;

use Domain\Estate\Models\Estate;
use Domain\Estate\Traits\ChangeEstateLocation;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

class ChangeEstateLocationConversation extends Conversation
{
    use ChangeEstateLocation;

    public Estate $estate;

    public function start(Nutgram $bot): void
    {
//        $this->estate = Estate::find($estate_id);

        $bot->sendMessage(
            text: "<b>Шаг 2 из 3</b>
Отправьте геолокацию вашего объекта. Для этого перейдите во вкладку прикрепить и отправьте геолокацию боту.",
            parse_mode: 'html'
        );

        $this->next('ChangeLocationStepTwo');
    }
}
