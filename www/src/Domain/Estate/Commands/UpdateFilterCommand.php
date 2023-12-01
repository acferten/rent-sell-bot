<?php

namespace Domain\Estate\Commands;

use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class UpdateFilterCommand extends Command
{
    public static function handle(Nutgram $bot): void
    {
        $bot->sendMessage(
            text: '🏠 Вы можете изменить параметры поиска недвижимости по кнопке ниже.',
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make('⚙ Настроить фильтр',
                    web_app: new WebAppInfo(env('NGROK_SERVER') . "/estates/filters"))
                )
        );
    }
}
