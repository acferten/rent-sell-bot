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
            text: "<b>Обновление локации</b>
📍 Отправьте геолокацию вашего объекта.
👉 Вставьте ссылку из Google Maps или отправьте текущую Геопозицию.",
            parse_mode: 'html'
        );

        $this->next('change');
    }

    public function change(Nutgram $bot): void
    {
        $this->estate = Estate::find($bot->getUserData('estate_id', $bot->userId()));

        if ($bot->message()->location) {
            $location = $bot->message()->location;

            $this->estate->update([
                'latitude' => $location->latitude,
                'longitude' => $location->longitude
            ]);

            $this->setLocationProperties($bot);
        } else {
            $this->estate->update([
                'google_link' => $bot->message()->text
            ]);
        }

        $bot->deleteMessage($bot->userId(), $bot->getUserData('preview_message_id'));

        SendPreviewMessageAction::execute($bot, $this->estate->id);
    }
}
