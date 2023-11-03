<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

use Domain\Estate\Actions\ApproveEstateAction;
use Domain\Estate\Actions\ConfirmEstateRelevanceAction;
use Domain\Estate\Actions\DeclineEstateAction;
use Domain\Estate\Actions\SendPreviewMessageAction;
use Domain\Estate\Conversations\ChangeEstateLocationConversation;
use Domain\Estate\Conversations\GetEstatesConversation;
use Domain\Estate\Conversations\GetFilteredEstatesConversation;
use Domain\Estate\Menu\CreateEstateMenu;
use Domain\Estate\Menu\UserEstatesMenu;
use Domain\Shared\Menu\StartMenu;
use SergiX44\Nutgram\Nutgram;

$bot->onText('Основные данные первого шага успешно переданы! 🥳', CreateEstateMenu::class);
$bot->onText('Основные данные первого шага успешно обновлены! 🥳', function (Nutgram $bot) {
    $bot->deleteMessage($bot->userId(), $bot->messageId());
    SendPreviewMessageAction::execute($bot);
});

$bot->onCommand('start', StartMenu::class);

$bot->onCommand('myestates', UserEstatesMenu::class);
$bot->onCommand('allestates', GetEstatesConversation::class);
$bot->onCommand('estates', GetFilteredEstatesConversation::class);

$bot->onCallbackQueryData('change location', ChangeEstateLocationConversation::class);
$bot->onCallbackQueryData('approve {estate_id}', ApproveEstateAction::class);
$bot->onCallbackQueryData('decline {estate_id}', DeclineEstateAction::class);
$bot->onCallbackQueryData('relevant {estate_id}', ConfirmEstateRelevanceAction::class);

