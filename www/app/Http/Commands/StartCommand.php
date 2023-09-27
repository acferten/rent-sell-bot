<?php

namespace App\Http\Commands;

use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Button;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Methods\Update;

class StartCommand extends Command
{
    use Update;

    protected string $name = 'start';
    protected string $description = 'Запуск / Перезапуск бота';

    public function handle(): void
    {
//        $this->getUpdates();

        $button1 = Button::make(['text' => 'Найти жилье в аренду', 'url' => 'start.ru']);
        $button2 = Button::make(['text' => 'Купить жильё', 'url' => 'vk.com']);
        $button3 = Button::make(['text' => 'Разместить объект недвижимости для аренды или продажи', 'url' => 'vk.com']);

        $keyboard = [
            [$button1], [$button2], [$button3]
        ];

        $reply_markup = Keyboard::make(['inline_keyboard' => $keyboard]);

        $this->replyWithMessage([
            'text' => 'Что вы хотите?',
            'reply_markup' => $reply_markup
        ]);
    }
}
