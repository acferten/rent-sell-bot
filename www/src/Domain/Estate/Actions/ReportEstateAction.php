<?php

namespace Domain\Estate\Actions;

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class ReportEstateAction
{
    public function __invoke(Nutgram $bot, $estate_id): void
    {
        $bot->answerCallbackQuery();
        $message = $bot->sendMessage(
            text: 'Пожалуйста, выберите причину',
            parse_mode: 'html',
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make('Объявление закрыто', callback_data: "reportReason{$estate_id} Объявление закрыто"))
                ->addRow(InlineKeyboardButton::make('Неверная цена', callback_data: "reportReason{$estate_id} Неверная цена"))
                ->addRow(InlineKeyboardButton::make('Неверное описание, фото', callback_data: "reportReason{$estate_id} Неверное описание, фото"))
                ->addRow(InlineKeyboardButton::make('Неверный адрес', callback_data: "reportReason{$estate_id} Неверный адрес"))
                ->addRow(InlineKeyboardButton::make('Продавец не отвечает', callback_data: "reportReason{$estate_id} Продавец не отвечает"))
                ->addRow(InlineKeyboardButton::make('Мошенник', callback_data: "reportReason{$estate_id} Мошенник"))
        );

        $bot->setUserData('report_menu_id', $message->message_id);
    }
}
