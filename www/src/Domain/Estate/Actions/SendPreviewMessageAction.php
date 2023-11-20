<?php

namespace Domain\Estate\Actions;

use Domain\Estate\Models\Estate;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class SendPreviewMessageAction
{
    public static function execute(Nutgram $bot, ?int $estate_id = null): void
    {
        if (!is_null($estate_id)) {
            $estate = Estate::find($estate_id);
        } else {
            $estate_id = $bot->getUserData('estate_id', $bot->userId());
            is_null($estate_id) ?
                $bot->sendMessage('Ошибка. Вы не создаете новый объект.') :
                $estate = Estate::find($estate_id);
        }

        $photo = fopen("photos/{$estate->main_photo}", 'r+');
        $message = $bot->sendPhoto(photo: InputFile::make($photo), caption: "Отлично! Вы заполнили объявление.\n
🎬 Обязательно нажмите <b>Предпросмотр</b>, чтобы понять, как будут видеть клиенты ваше подробное объявление.
🧞‍♂️ Нажмите <b>Редактировать</b>, если хотите внести изменения.
🐶 Если вам нравится ваше объявление, то нажимайте <b>Оплатить и разместить.</b>",
            parse_mode: 'html',
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make('🎬 Предпросмотр',
                    web_app: new WebAppInfo(env('NGROK_SERVER') . "/estates/{$estate->id}")))
                ->addRow(InlineKeyboardButton::make('🐶 Оплатить и разместить',
                    callback_data: "pay"))
                ->addRow(InlineKeyboardButton::make('🧞‍♂️ Редактировать',
                    web_app: new WebAppInfo(env('NGROK_SERVER') . "/estates/{$estate->id}/edit")))
                ->addRow(InlineKeyboardButton::make('✍️ Редактировать локацию объекта',
                    callback_data: "change location"))
                ->addRow(InlineKeyboardButton::make('🙅‍♂️ Отменить размещение',
                    callback_data: "cancel publish")));

        $bot->setUserData('preview_message_id', $message->message_id, $bot->userId());
    }
}
