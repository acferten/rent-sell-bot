<?php

namespace Domain\Estate\Menu;

use Domain\Estate\Models\Estate;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;

class CreateEstateSecondStep extends InlineMenu
{
    public function start(Nutgram $bot): void
    {
        $bot->sendMessage(
            text: "<b>Шаг 2 из 3</b>
Отправьте геолокацию вашего объекта.",
            parse_mode: 'html'
        );
        $this->next('location');
    }

    public function location(Nutgram $bot)
    {
        $location = $bot->message()->location;

        Estate::where(['user_id' => $bot->userId()])->latest()->update([
            'latitude' => $location->latitude,
            'longitude' => $location->longitude
        ]);

        $bot->sendMessage('Локация добавлена к объекту.');
    }

    public function none(Nutgram $bot)
    {
        $this->end();
    }
}
