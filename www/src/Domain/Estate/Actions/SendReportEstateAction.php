<?php

namespace Domain\Estate\Actions;

use Domain\Estate\Models\Estate;
use Domain\Estate\ViewModels\GetEstateViewModel;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class SendReportEstateAction
{
    public function __invoke(Nutgram $bot, $estate_id, $reason): void
    {
        $bot->deleteMessage($bot->userId(), $bot->getUserData('report_menu_id'));
        $bot->deleteUserData('report_menu_id');

        $estate = Estate::find($estate_id);

        $bot->sendMessage("<b>😡 Новая жалоба на объявление</b>\n\n" .
            GetEstateViewModel::get($estate) .
            "\nОт: {$bot->user()->username}, {$bot->user()->first_name}" .
            "\nПричина: {$reason}",
            '-1001875753187', parse_mode: 'html', disable_notification: true,
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make('👀 Посмотреть объявление',
                    url: (env('NGROK_SERVER') . "/estate/{$estate->id}")))
                ->addRow(InlineKeyboardButton::make('✏ Написать владельцу',
                    url: $estate->user->getTelegramUrl()))
        );
    }
}
