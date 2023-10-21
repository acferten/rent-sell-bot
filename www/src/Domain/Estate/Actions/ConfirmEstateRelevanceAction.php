<?php

namespace Domain\Estate\Actions;

use Carbon\Carbon;
use Domain\Estate\Models\Estate;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;

class ConfirmEstateRelevanceAction
{
    public static function execute(Nutgram $bot, int $estate_id)
    {
        $estate = Estate::where('id', $estate_id)->first()->update([
            'relevance_date' => Carbon::now()
        ]);

        Log::debug('message_send');

        $bot->answerWebAppQuery();
        $bot->sendMessage('done relevant', $estate->user_id);

    }
}
