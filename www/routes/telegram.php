<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

// commands
$bot->onCommand('start', \Domain\Shared\Menu\StartMenu::class);
$bot->onCommand('myobjects', \Domain\Estate\Menu\UserEstatesMenu::class);
$bot->onCommand('estates', \Domain\Estate\Menu\GetEstatesMenu::class);
$bot->onCallbackQueryData('main_clicked', \Domain\Estate\Actions\CreateEstateAction::class);

