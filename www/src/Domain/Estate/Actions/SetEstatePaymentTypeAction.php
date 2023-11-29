<?php

namespace Domain\Estate\Actions;

use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Messages\EstateCardMessage;
use Domain\Estate\Models\Estate;
use SergiX44\Nutgram\Nutgram;

class SetEstatePaymentTypeAction
{
    public function __invoke(Nutgram $bot, string $paid_with, int $estate_id): void
    {
        $estate = Estate::find($estate_id);

        if ($estate->paid_with) {
            $bot->answerCallbackQuery(text: 'У объявления уже выбран тип оплаты.');
            return;
        }

        $estate->update(['paid_with' => $paid_with]);
    }
}
