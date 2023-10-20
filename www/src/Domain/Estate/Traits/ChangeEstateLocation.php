<?php

namespace Domain\Estate\Traits;

use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

trait ChangeEstateLocation
{
    public function handleChangeLocation(Nutgram $bot): void
    {
        $bot->sendMessage(
            text: "<b>Шаг 2 из 3</b>
Отправьте геолокацию вашего объекта. Для этого перейдите во вкладку прикрепить и отправьте геолокацию боту.",
            parse_mode: 'html'
        );
        $this->closeMenu();

        $this->next('ChangeLocationStepTwo');
    }

    public function ChangeLocationStepTwo(Nutgram $bot): void
    {
        $location = $bot->message()->location;

        $this->estate->update([
            'latitude' => $location->latitude,
            'longitude' => $location->longitude
        ]);

        $this->setLocationProperties($bot);

        $this->setPreview();
        $this->clearButtons()->menuText($this->preview, ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('Все верно, перейти к оплате ✅', callback_data: 'payment@handlePayment'))
//            ->addButtonRow(InlineKeyboardButton::make('Изменить данные первого шага ✍️', callback_data: 'changeEstate@handleChangeFirstStep'))
            ->addButtonRow(InlineKeyboardButton::make('Изменить локацию объекта ✍️', callback_data: 'changeLocation@handleChangeLocation'))
//            ->addButtonRow(InlineKeyboardButton::make('Просмотр прикрепленных изображений 👀', callback_data: 'images@handleViewImages'))
            ->addButtonRow(InlineKeyboardButton::make('Отменить публикацию объявления ❌', callback_data: 'cancel@handleConfirmCancelEstate'))
            ->showMenu();
    }
}
