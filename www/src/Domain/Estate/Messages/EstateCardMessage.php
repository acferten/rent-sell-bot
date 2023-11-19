<?php

namespace Domain\Estate\Messages;

use Domain\Estate\Models\Estate;
use Domain\Estate\ViewModels\GetEstateViewModel;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class EstateCardMessage
{
    public static function send(Estate $estate, int $user_id)
    {
        $photo = fopen("photos/{$estate->main_photo}", 'r+');

        $estate->update([
            'views' => $estate->views + 1
        ]);

        Telegram::sendPhoto(photo: InputFile::make($photo), chat_id: $user_id,
            caption: GetEstateViewModel::get($estate), parse_mode: 'html',
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make('🔍 Посмотреть подробнее',
                    web_app: new WebAppInfo(env('NGROK_SERVER') . "/estates/{$estate->id}")))
                ->addRow(InlineKeyboardButton::make('🥸 Написать владельцу', url: $estate->user->getTelegramUrl()))
                ->addRow(InlineKeyboardButton::make('➡ Следующее объявление', callback_data: 'next'))
        );
    }
}
