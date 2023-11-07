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

        $bot->sendMessage('Поздравляем!<b>
Вы успешно разместили ваш объект. Теперь все соискатели жилья видят его.
Потенциальные клиенты будут писать вам напрямую.</b>', $estate->user_id, parse_mode: 'html');
        $bot->deleteMessage('-1001875753187', $bot->callbackQuery()->message->message_id);
    }
}
