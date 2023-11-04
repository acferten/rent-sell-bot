<?php

namespace Domain\Estate\Conversations;

use Domain\Estate\Actions\SendPreviewMessageAction;
use Domain\Estate\Models\Estate;
use Domain\Estate\Traits\SetLocationProperties;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;

class ChangeEstateLocationConversation extends Conversation
{
    use SetLocationProperties;

    public Estate $estate;

    public function start(Nutgram $bot): void
    {
        $this->estate = Estate::find($bot->getUserData('estate_id', $bot->userId()));

        $bot->sendMessage(
            text: "<b>Шаг 2 из 3</b>
Отправьте геолокацию вашего объекта. Для этого перейдите во вкладку прикрепить и отправьте геолокацию боту.",
            parse_mode: 'html'
        );

        $this->next('change');
    }

    public function change(Nutgram $bot): void
    {
        $location = $bot->message()->location;

        $this->estate->update([
            'latitude' => $location->latitude,
            'longitude' => $location->longitude
        ]);

        $this->setLocationProperties($bot);

        // clear previous preview
        $bot->deleteMessage($bot->userId(), $bot->messageId() - 2);
        $bot->deleteMessage($bot->userId(), $bot->messageId() - 1);
        $bot->deleteMessage($bot->userId(), $bot->messageId());

        SendPreviewMessageAction::execute($bot, $this->estate->id);
    }
}
