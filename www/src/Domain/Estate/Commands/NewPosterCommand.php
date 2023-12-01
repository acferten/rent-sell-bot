<?php

namespace Domain\Estate\Commands;

use Domain\Estate\Enums\EstateCallbacks;
use Domain\Shared\Enums\MessageText;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class NewPosterCommand extends Command
{
    public static function handle(Nutgram $bot): void
    {
        $bot->sendMessage(
            "<b>Шаг 1 из 3</b>
Заполните данные об объекте, который Вы хотите сдать в долгосрочную аренду. Это займёт не более 5 минут.",
            parse_mode: 'html',
            reply_markup: InlineKeyboardMarkup::make()->addRow(InlineKeyboardButton::make(
                '✍️ Заполнить форму',
                web_app: new WebAppInfo(env('NGROK_SERVER') . "/estates/create"))
            )->addRow(InlineKeyboardButton::make(
                EstateCallbacks::CallManager->value,
                url: MessageText::ManagerUrl->value
            )));
    }
}
