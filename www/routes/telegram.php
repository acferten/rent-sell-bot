<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

use Domain\Estate\Actions\ApproveEstateAction;
use Domain\Estate\Actions\ConfirmEstateRelevanceAction;
use Domain\Estate\Menu\CreateEstateSecondStep;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;
use function Nutgram\Laravel\Support\webAppData;

$bot->onCommand('start', \Domain\Shared\Menu\StartMenu::class);
$bot->onCommand('myestates', \Domain\Estate\Menu\UserEstatesMenu::class);
$bot->onCommand('allestates', \Domain\Estate\Menu\GetEstatesConversation::class);
$bot->onCommand('estates', \Domain\Estate\Menu\GetFilteredEstatesConversation::class);
$bot->onText('Основные данные первого шага успешно переданы! 🥳', \Domain\Estate\Menu\CreateEstateSecondStep::class);

$bot->onCallbackQueryData('approve {estate_id}', function (Nutgram $bot, $estate_id) {
    ApproveEstateAction::execute($bot, $estate_id);
});

$bot->onCallbackQueryData('successUpdatedFirstStepEstate {estate_id}', function (Nutgram $bot, $estate_id) {
    CreateEstateSecondStep::getPreviewLayout($bot, $estate_id);
});

$bot->onCallbackQueryData('relevant {estate_id}', function (Nutgram $bot, $estate_id) {
    ConfirmEstateRelevanceAction::execute($bot, $estate_id);
});

$bot->onCallbackQueryData('handlePayment {estate_id}', function (Nutgram $bot, $estate_id) {
    (new Domain\Estate\Actions\handlePayment)->handlePayment($bot, $estate_id);
});
