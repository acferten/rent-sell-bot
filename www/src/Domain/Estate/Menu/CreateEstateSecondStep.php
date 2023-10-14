<?php

namespace Domain\Estate\Menu;

use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class CreateEstateSecondStep extends InlineMenu
{
    public function start(Nutgram $bot): void
    {
        $bot->sendMessage(
            text: "<b>Шаг 2 из 3</b>
Отправьте геолокацию вашего объекта.",
            parse_mode: 'html',
            reply_markup: ReplyKeyboardMarkup::make()->addRow(
                KeyboardButton::make('Указать геолокацию 🩰', request_location: true),
            )
        );
    }

    public function none(Nutgram $bot)
    {
        $this->end();
    }
}
