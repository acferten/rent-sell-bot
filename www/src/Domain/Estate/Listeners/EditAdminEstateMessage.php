<?php

namespace Domain\Estate\Listeners;

use Domain\Estate\Events\EstateUpdatedEvent;
use Domain\Estate\ViewModels\AdminEstatePreviewViewModel;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class EditAdminEstateMessage
{
    public function handle(EstateUpdatedEvent $event): void
    {
        $reply_markup = InlineKeyboardMarkup::make()
            ->addRow(InlineKeyboardButton::make('👉 Подробнее',
                url: env('NGROK_SERVER') . "/estates/{$event->estate->id}"))
            ->addRow(InlineKeyboardButton::make('👨‍💼 Написать владельцу',
                url: $event->estate->user->getTelegramUrl()));

        if (is_null($event->estate->paid_with)) {
            $reply_markup->addRow(InlineKeyboardButton::make('💳 Paid to Bank BRI',
                callback_data: "paid {$event->estate->id}"))
                ->addRow(InlineKeyboardButton::make('💎 Paid to Tinkoff',
                    callback_data: "paid {$event->estate->id}"));
        }

        if ($event->estate->status()->canBeChanged()) {
            $reply_markup->addRow(InlineKeyboardButton::make('🔴 Снять с размещения',
                callback_data: "decline {$event->estate->id}"));
        }

        if ($event->estate->admin_message_id) {
            Telegram::editMessageCaption(
                chat_id: env('ADMIN_CHAT_ID'),
                message_id: $event->estate->admin_message_id,
                caption: AdminEstatePreviewViewModel::get($event->estate),
                parse_mode: 'html',
                reply_markup: $reply_markup
            );
        }
    }
}
