<?php

namespace Domain\Estate\Commands;

use Domain\Estate\Enums\EstateCallbacks;
use Domain\Shared\Enums\MessageText;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class ContactManagerCommand extends Command
{
    public static function handle(Nutgram $bot): void
    {
        $bot->sendMessage("🙋 Наши менеджеры ответят на любые вопросы касаемо работы сервиса.
 Нам очень важна обратная связь от вас.

 Мы работаем каждый день с 09:00 до 20:00 (по Бали).", reply_markup: InlineKeyboardMarkup::make()->addRow(InlineKeyboardButton::make(
            EstateCallbacks::CallManager->value,
            url: MessageText::ManagerUrl->value
        )));
    }
}
