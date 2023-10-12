<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

// commands
$bot->onCommand('start', \Domain\Shared\Menu\StartMenu::class);
$bot->onCommand('myobjects', \Domain\Estate\Menu\UserEstatesMenu::class);



