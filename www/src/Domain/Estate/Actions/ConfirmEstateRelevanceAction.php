<?php

namespace Domain\Estate\Actions;

use Carbon\Carbon;
use Domain\Estate\Models\Estate;
use SergiX44\Nutgram\Nutgram;

class ConfirmEstateRelevanceAction
{
    public static function execute(Nutgram $bot, int $estate_id)
    {
        Estate::where('id', $estate_id)->first()
            ->update(['relevance_date' => Carbon::now()]);
        $bot->answerCallbackQuery();
        $bot->deleteMessage($bot->userId(), $bot->messageId());
        $bot->sendMessage('Актуальность подтверждена');
    }
}
