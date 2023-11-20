<?php

namespace Domain\Estate\Messages;

use Domain\Estate\Models\Estate;
use Domain\Estate\ViewModels\AdminEstatePreviewViewModel;
use Domain\Estate\ViewModels\GetEstateViewModel;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class AdminChatEstateCardMessage
{
    public static function send(Estate $estate, int $user_id)
    {
        $photo = fopen("photos/{$estate->main_photo}", 'r+');

        $preview = AdminEstatePreviewViewModel::get($estate);
        $user_url = 'https://t.me/' . $estate->user->username;

        Telegram::sendPhoto(InputFile::make($photo), '-1001875753187',
            caption: $preview,
            parse_mode: 'html',
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make('👉 Подробнее',
                    url: env('NGROK_SERVER') . "/estate/{$estate->id}"))
                ->addRow(InlineKeyboardButton::make('👨‍💼 Написать владельцу', url: $user_url))
                ->addRow(InlineKeyboardButton::make('🌟 Разместить', callback_data: "approve {$estate->id}"))
                ->addRow(InlineKeyboardButton::make('🔴 Отклонить', callback_data: "decline {$estate->id}"))
                ->addRow(InlineKeyboardButton::make('🧾 Чек об оплате', url: "https://vk.com"))
        );
    }
}
