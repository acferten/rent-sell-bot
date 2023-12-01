<?php

namespace Domain\Estate\Listeners;

use Domain\Estate\Enums\EstateStatus;
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
                url: $event->estate->user->getTelegramUrl()))
            ->addRow(InlineKeyboardButton::make('✍️ Редактировать',
                url: env('NGROK_SERVER') . "admin/estates/{$event->estate->id}/edit"));

        if (is_null($event->estate->paid_with)) {
            $reply_markup->addRow(InlineKeyboardButton::make('💳 Paid to Bank BRI',
                callback_data: "payment BankBRI {$event->estate->id}"))
                ->addRow(InlineKeyboardButton::make('💎 Paid to Tinkoff',
                    callback_data: "payment Tinkoff {$event->estate->id}"));
        }

        if ($event->estate->status()->canBeChanged()) {
            $reply_markup->addRow(InlineKeyboardButton::make('🔴 Снять с размещения',
                callback_data: "close {$event->estate->id}"));
        } else {
            $reply_markup->addRow(InlineKeyboardButton::make('🌟 Разместить', callback_data: "approve {$event->estate->id}"));
        }

        if ($event->estate->status == EstateStatus::pending->value) {
            $reply_markup->addRow(InlineKeyboardButton::make('🔴 Отклонить', callback_data: "decline {$event->estate->id}"));
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
