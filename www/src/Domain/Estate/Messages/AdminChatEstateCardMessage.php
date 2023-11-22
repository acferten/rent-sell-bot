<?php

namespace Domain\Estate\Messages;

use Domain\Estate\Models\Estate;
use Domain\Estate\ViewModels\AdminEstatePreviewViewModel;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class AdminChatEstateCardMessage
{
    public static function send(Estate $estate, int $user_id): void
    {
        $photo = InputFile::make(fopen("photos/{$estate->main_photo}", 'r+'));

        $message = Telegram::sendPhoto(
            photo: $photo,
            chat_id: env('ADMIN_CHAT_ID'),
            caption: AdminEstatePreviewViewModel::get($estate),
            parse_mode: 'html',
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make('👉 Подробнее',
                    url: env('NGROK_SERVER') . "/estate/{$estate->id}"))
                ->addRow(InlineKeyboardButton::make('👨‍💼 Написать владельцу',
                    url: $estate->user->getTelegramUrl()))
                ->addRow(InlineKeyboardButton::make('🌟 Разместить', callback_data: "approve {$estate->id}"))
                ->addRow(InlineKeyboardButton::make('🔴 Отклонить', callback_data: "decline {$estate->id}"))
                ->addRow(InlineKeyboardButton::make('🧾 Чек об оплате', url: "https://vk.com"))
        );

        $estate->update([
            'admin_message_id' => $message->message_id
        ]);
    }
}
