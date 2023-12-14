<?php

namespace Domain\Estate\Menu;

use Domain\Estate\Actions\SendPreviewMessageAction;
use Domain\Estate\Models\Estate;
use Domain\Estate\Traits\SetLocationProperties;
use Domain\Shared\Models\User;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardRemove;

class CreateEstateMenu extends InlineMenu
{
    use SetLocationProperties;

    public Estate $estate;

    public function start(Nutgram $bot): void
    {
        if (User::find($bot->userId())->phone) {
            $bot->sendMessage(
                text: "<b>Шаг 2 из 2</b>
📍 Отправьте геолокацию вашего объекта.
👉 Вставьте ссылку из Google Maps или отправьте текущую Геопозицию.",
                parse_mode: 'html', reply_markup: ReplyKeyboardRemove::make(true)
            );
            $this->next('location');
        } else {
            $bot->sendMessage(
                text: "<b>Шаг 2 из 3</b>\nОтправьте ваши контактные данные Telegram для связи клиентов с вами.",
                parse_mode: 'html',
                reply_markup: ReplyKeyboardMarkup::make(resize_keyboard: true, one_time_keyboard: true)->addRow(
                    KeyboardButton::make('📞 Поделиться контактными данными', request_contact: true))
            );
            $this->next('contact');
        }
    }

    public function contact(Nutgram $bot): void
    {
        User::where(['id' => $bot->userId()])
            ->first()
            ->update([
                'phone' => $bot->message()->contact->phone_number
            ]);

        $bot->sendMessage(
            text: "<b>Шаг 3 из 3</b>
📍 Отправьте геолокацию вашего объекта.
👉 Вставьте ссылку из Google Maps или отправьте текущую Геопозицию.",
            parse_mode: 'html', reply_markup: ReplyKeyboardRemove::make(true)
        );

        $this->next('location');
    }

    public function location(Nutgram $bot): void
    {
        $this->estate = Estate::find($bot->getUserData('estate_id', $bot->userId()));

        if ($bot->message()->location) {
            $location = $bot->message()->location;

            $this->estate->update([
                'latitude' => $location->latitude,
                'longitude' => $location->longitude
            ]);

            $this->setLocationProperties($bot);
        } else {
            $this->estate->update([
                'google_link' => $bot->message()->text
            ]);
        }

        SendPreviewMessageAction::execute($bot, $this->estate->id);
    }

    public function none(Nutgram $bot): void
    {
        $this->end();
    }
}
