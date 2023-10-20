<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

use Domain\Estate\Actions\ApproveEstateAction;
use Domain\Estate\Actions\DeclineEstateAction;
use SergiX44\Nutgram\Nutgram;

$bot->onCommand('start', \Domain\Shared\Menu\StartMenu::class);
$bot->onCommand('myobjects', \Domain\Estate\Menu\UserEstatesMenu::class);
$bot->onCommand('estates', \Domain\Estate\Menu\GetEstatesMenu::class);
$bot->onText('Основные данные первого шага успешно переданы! 🥳', \Domain\Estate\Menu\CreateEstateSecondStep::class);

$bot->onCallbackQueryData('approve {estate_id}', function (Nutgram $bot, $estate_id) {
    ApproveEstateAction::execute($bot, $estate_id);
});

$bot->onCallbackQueryData('decline {estate_id}', function (Nutgram $bot, $estate_id) {
    DeclineEstateAction::execute($bot, $estate_id);
});
