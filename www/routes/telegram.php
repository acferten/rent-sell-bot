<?php
/** @var SergiX44\Nutgram\Nutgram $bot */

use Domain\Estate\Actions\ApproveEstateAction;
use Domain\Estate\Actions\ConfirmEstateRelevanceAction;
use Domain\Estate\Actions\DeclineEstateAction;
use Domain\Estate\Actions\ReportEstateAction;
use Domain\Estate\Actions\SendPreviewMessageAction;
use Domain\Estate\Actions\SendReportEstateAction;
use Domain\Estate\Conversations\ChangeEstateLocationConversation;
use Domain\Estate\Conversations\GetEstatesConversation;
use Domain\Estate\Conversations\GetFilteredEstatesConversation;
use Domain\Estate\Menu\CancelEstatePublicationMenu;
use Domain\Estate\Menu\CreateEstateMenu;
use Domain\Estate\Menu\EstatePaymentMenu;
use Domain\Estate\Menu\UserEstatesMenu;
use Domain\Shared\Menu\StartMenu;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Exceptions\TelegramException;


$bot->onCommand('start', StartMenu::class);

$bot->onCommand('myestates', UserEstatesMenu::class);
$bot->onCommand('allestates', GetEstatesConversation::class);
$bot->onCommand('estates', GetFilteredEstatesConversation::class);

$bot->onCallbackQueryData('change location', ChangeEstateLocationConversation::class);
$bot->onCallbackQueryData('cancel publish', CancelEstatePublicationMenu::class);
$bot->onCallbackQueryData('pay', EstatePaymentMenu::class);

//$bot->onCallbackQueryData('report {estate_id}', ReportEstateAction::class);
//$bot->onCallbackQueryData('reportReason{estate_id} {reason}', SendReportEstateAction::class)
//    ->where(['estate_id' => '[0-9]+']);

$bot->onCallbackQueryData('approve {estate_id}', ApproveEstateAction::class);
$bot->onCallbackQueryData('decline {estate_id}', DeclineEstateAction::class);
$bot->onCallbackQueryData('relevant {estate_id}', ConfirmEstateRelevanceAction::class);

$bot->onText('Данные первого шага успешно переданы! 🥳', CreateEstateMenu::class);
$bot->onText('Данные первого шага успешно обновлены! 🥳', function (Nutgram $bot) {
    $bot->deleteMessage($bot->userId(), $bot->getUserData('preview_message_id'));
    SendPreviewMessageAction::execute($bot);
});

$bot->onException(function (Nutgram $bot, \Throwable $exception) {
    $bot->sendMessage($exception->getMessage());
    $bot->sendMessage("File: " . $exception->getFile());
    $bot->sendMessage("Line: " . $exception->getLine());
    Log::error($exception);
});

$bot->onApiError(function (Nutgram $bot, TelegramException $exception) {
    $bot->sendMessage($exception->getMessage());
    $bot->sendMessage("File: " . $exception->getFile());
    $bot->sendMessage("Line: " . $exception->getLine());
    Log::error($exception);
});

$bot = new Nutgram($_ENV['NGROK_SERVER']);
$bot->setRunningMode(Webhook::class);

$bot->run();
