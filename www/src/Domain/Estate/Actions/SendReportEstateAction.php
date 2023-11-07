<?php

namespace Domain\Estate\Actions;

use Domain\Estate\Models\Estate;
use Domain\Estate\ViewModels\GetEstateViewModel;
use SergiX44\Nutgram\Nutgram;

class SendReportEstateAction
{
    public function __invoke(Nutgram $bot, $estate_id, $reason): void
    {
        $bot->deleteMessage($bot->userId(), $bot->getUserData('report_menu_id'));
        $bot->deleteUserData('report_menu_id');

        $estate = Estate::find($estate_id);

        $bot->sendMessage("<b>😡 Новая жалоба на объявление</b>\n" .
            GetEstateViewModel::get($estate) .
            "\nОт: {$bot->user()->username}, {$bot->user()->first_name}" .
            "\nПричина: {$reason}",
            '-1001875753187', parse_mode: 'html', disable_notification: true);

    }
}
