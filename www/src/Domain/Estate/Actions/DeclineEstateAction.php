<?php

namespace Domain\Estate\Actions;

use Domain\Estate\Models\Estate;
use SergiX44\Nutgram\Nutgram;


class DeclineEstateAction
{
    public function __invoke(Nutgram $bot, int $estate_id): void
    {
        $estate = Estate::where('id', $estate_id)->first();

        $bot->sendMessage('Ваша публикация отклонена. Возможно, вы ошиблись в каком-то шаге, либо оплата не дошла до нас. Попробуйте еще раз.', $estate->user_id);

        $estate->delete();
        $bot->deleteMessage('-1001875753187', $bot->callbackQuery()->message->message_id);
    }
}
