<?php

namespace Domain\Estate\Menu;

use Domain\Estate\Actions\SendPreviewMessageAction;
use Domain\Estate\Models\Estate;
use Domain\Estate\Traits\CancelEstatePublication;
use Domain\Estate\Traits\ChangeEstateLocation;
use Domain\Estate\Traits\HandleEstatePayment;
use Domain\Shared\Models\Actor\User;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardRemove;

class CreateEstateMenu extends InlineMenu
{
    use ChangeEstateLocation;
    use HandleEstatePayment;
    use CancelEstatePublication;

    public Estate $estate;

    public function start(Nutgram $bot): void
    {
        $bot->sendMessage(
            text: "<b>Шаг 2 из 3</b>\nОтправьте ваши контактные данные Telegram.",
            parse_mode: 'html',
            reply_markup: ReplyKeyboardMarkup::make(resize_keyboard: true, one_time_keyboard: true)->addRow(
                KeyboardButton::make('📞 Поделиться контактными данными', request_contact: true))
        );

        $this->next('contact');
    }

    public function contact(Nutgram $bot): void
    {
        User::where(['id' => $bot->userId()])
            ->first()
            ->update([
                'phone' => $bot->message()->contact->phone_number
            ]);

        $bot->sendMessage('Контактные данные сохранены.',
            reply_markup: ReplyKeyboardRemove::make(true));

        $bot->sendMessage(
            text: "<b>Шаг 3 из 3</b>
Отправьте геолокацию вашего объекта. Для этого перейдите во вкладку прикрепить и отправьте геолокацию боту.",
            parse_mode: 'html',
        );

        $this->next('location');
    }

    public function location(Nutgram $bot): void
    {
        $location = $bot->message()->location;

        $this->estate = Estate::where(['user_id' => $bot->userId()])
            ->latest()->first();

        $this->estate->update([
            'latitude' => $location->latitude,
            'longitude' => $location->longitude
        ]);;

        $this->setLocationProperties($bot);

        SendPreviewMessageAction::execute($bot, $this->estate->id);
    }

    public function none(Nutgram $bot): void
    {
        $this->end();
    }
}
