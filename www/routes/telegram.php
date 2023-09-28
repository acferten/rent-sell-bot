<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

// commands
$bot->onCommand('start', \Domain\Shared\Menu\StartMenu::class);

$bot->onCommand('menu', \App\Http\Controllers\ChooseColorMenu::class);

