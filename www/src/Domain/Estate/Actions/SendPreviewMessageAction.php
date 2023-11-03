<?php

namespace Domain\Estate\Actions;

use Domain\Estate\Models\Estate;
use Domain\Estate\ViewModels\PreviewCreatedEstateViewModel;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class SendPreviewMessageAction
{
    public static function execute(Nutgram $bot, ?int $estate_id): void
    {
        $estate = $estate_id ? Estate::find($estate_id) :
            Estate::where('user_id', $bot->userId())->latest()->first();

        $preview = PreviewCreatedEstateViewModel::get($estate);
        $photo = fopen("photos/{$estate->main_photo}", 'r+');
        $bot->sendPhoto(photo: InputFile::make($photo), caption: $preview,
            parse_mode: 'html',
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make('👀 Посмотреть подробнее',
                    web_app: new WebAppInfo(env('NGROK_SERVER') . "/estate/{$estate->id}")))
                ->addRow(InlineKeyboardButton::make('✅ Все верно, перейти к оплате',
                    callback_data: "pay {$estate->id}"))
                ->addRow(InlineKeyboardButton::make('◀️ Вернуться к первому шагу',
                    web_app: new WebAppInfo(env('NGROK_SERVER') . "/estate/{$estate->id}/edit")))
                ->addRow(InlineKeyboardButton::make('✍️ Изменить локацию объекта',
                    callback_data: "change location {$estate->id}"))
                ->addRow(InlineKeyboardButton::make('❌ Отменить публикацию объявления',
                    callback_data: "cancel publish {$estate->id}")));
    }
}
