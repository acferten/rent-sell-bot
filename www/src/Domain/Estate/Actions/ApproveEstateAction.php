<?php

namespace Domain\Estate\Actions;

use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Messages\EstateCardMessage;
use Domain\Estate\Models\Estate;
use SergiX44\Nutgram\Nutgram;

class ApproveEstateAction
{
    public function __invoke(Nutgram $bot, int $estate_id): void
    {
        $estate = Estate::find($estate_id);

        if ($estate->status()->canBeChanged()) {
            $bot->answerCallbackQuery(text: 'Объявление уже имеет статус "Активно"');
            return;
        }

        $estate->update(['status' => EstateStatus::active->value]);

        $bot->sendMessage("🥳 Поздравляем!
Вы успешно разместили ваш объект.

Теперь его будут видеть сотни пользователей 🔑GetKeysBot.
Потенциальные клиенты будут писать вам напрямую в Telegram.
Отвечайте оперативно и уважительно.

🪄 Желаем сдать жильё прямо сегодня!", $estate->user_id, parse_mode: 'html');

        EstateCardMessage::send($estate, $estate->user_id);
    }
}
