<?php

namespace App\Http\Commands;

use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Запуск / Перезапуск бота';

    public function handle(): void
    {
        $message = '<b>жирный текст</b>'
            . PHP_EOL . '<i>курсивный текст</i>'
            . PHP_EOL . '<u>подчеркнутный текст</u>'
            . PHP_EOL . '<s>перечеркнутный текст</s>'
            . PHP_EOL . '<span class="tg-spoiler">Какой то спойлер Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reprehenderit, voluptate!</span>'
            . PHP_EOL . '<b>жирный текст <i>еще и курсивный <s>еще и перечеркнутый </s></i></b>'
            . PHP_EOL . '<a href="https://www.example.com/">Ссылка в текстом</a>'
            . PHP_EOL . '<a href="tg://user?id=264493118">упоминание пользователя</a>'
            . PHP_EOL . '<code>код фиксированной ширины</code>'
            . PHP_EOL . '<pre>предварительно отформатированный блок кода фиксированной ширины</pre>'
            . PHP_EOL . '<pre><code class="language-python">предварительно отформатированный блок кода фиксированной ширины, написанный на языке программирования Python</code></pre>';

        $this->replyWithMessage([
            'text' => $message,
            'parse_mode' => 'HTML'
        ]);
    }
}
