<?php

namespace Domain\Estate\Actions;

use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Messages\EstateCardMessage;
use Domain\Estate\Models\Estate;
use SergiX44\Nutgram\Nutgram;

class CloseEstateAction
{
    public function __invoke(Nutgram $bot, int $estate_id): void
    {
        $estate = Estate::find($estate_id);

        if ($estate->status == EstateStatus::closed->value) {
            $bot->answerCallbackQuery(text: 'Объявление уже имеет статус "Закрыто"');
            return;
        }

        $estate->update(['status' => EstateStatus::closed->value]);

        $bot->sendMessage("Ваше объявление {$estate->title} было снято с размещения администратором.", $estate->user_id, parse_mode: 'html');

        EstateCardMessage::send($estate, $estate->user_id);
    }
}
