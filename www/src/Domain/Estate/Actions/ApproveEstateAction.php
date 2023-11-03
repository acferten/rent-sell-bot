<?php

namespace Domain\Estate\Actions;

use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use SergiX44\Nutgram\Nutgram;

class ApproveEstateAction
{
    public function __invoke(Nutgram $bot, int $estate_id): void
    {
        $estate = Estate::where('id', $estate_id)->first();

        $estate->update(['status' => EstateStatus::active]);

        $bot->sendMessage('Ваша публикация одобрена и объявление теперь активно.', $estate->user_id);
        $bot->deleteMessage('-1001875753187', $bot->callbackQuery()->message->message_id);
    }
}
