<?php

namespace App\Http\Commands;

use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Button;
use Telegram\Bot\Keyboard\Keyboard;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Запуск / Перезапуск бота';

    public function handle(): void
    {
        $button1 = Button::make(['text' => 'button1', 'url' => 'vk.com']);
        $keyboard = [[$button1]];
        $reply_markup = Keyboard::make(['inline_keyboard' => $keyboard]);
        $this->replyWithMessage([
            'text' => 'test inline buttons',
            'reply_markup' => $reply_markup
        ]);

    }
}
