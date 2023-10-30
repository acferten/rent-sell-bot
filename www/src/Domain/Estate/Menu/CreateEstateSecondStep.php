<?php

namespace Domain\Estate\Menu;

use Domain\Estate\Enums\CreateEstateText;
use Domain\Estate\Models\Estate;
use Domain\Estate\Traits\CancelEstatePublication;
use Domain\Estate\Traits\ChangeEstateLocation;
use Domain\Estate\Traits\HandleEstatePayment;
use Domain\Estate\ViewModels\PreviewCreatedEstateViewModel;
use Domain\Shared\Models\Actor\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardRemove;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class CreateEstateSecondStep extends InlineMenu
{
    use ChangeEstateLocation;
    use HandleEstatePayment;
    use CancelEstatePublication;

    public Estate $estate;

    public function start(Nutgram $bot): void
    {
        $bot->sendMessage(
            text: "<b>Шаг 2 из 3</b>
Отправьте геолокацию вашего объекта. Для этого перейдите во вкладку прикрепить и отправьте геолокацию боту.",
            parse_mode: 'html'
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

        $bot->sendMessage(
            text: "<b>Шаг 3 из 3</b>
Отправьте ваши контактные данные Telegram.",
            parse_mode: 'html',
            reply_markup: ReplyKeyboardMarkup::make(resize_keyboard: true, one_time_keyboard: true)->addRow(
                KeyboardButton::make('📞 Поделиться контактными данными', request_contact: true)
            ),
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

        $this->getPreviewLayout($bot);
    }

    public function getPreviewLayout(Nutgram $bot): void
    {
        $preview = PreviewCreatedEstateViewModel::get($this->estate);
        $this->clearButtons();
        $photo = fopen("photos/{$this->estate->main_photo}", 'r+');
        $bot->sendPhoto(photo: InputFile::make($photo), caption: $preview,
            parse_mode: 'html',
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make('👀 Посмотреть подробнее',
                    web_app: new WebAppInfo(CreateEstateText::EstateUrl->value . "/{$this->estate->id}")),
                )->addRow(InlineKeyboardButton::make('✅ Все верно, перейти к оплате', callback_data: 'payment@handlePayment'))
                ->addRow(InlineKeyboardButton::make('◀️ Вернуться к первому шагу', web_app: new WebAppInfo(CreateEstateText::EstateUrl->value . "/{$this->estate->id}/edit")))
                ->addRow(InlineKeyboardButton::make('✍️ Изменить локацию объекта', callback_data: 'changeLocation@handleChangeLocation'))
                ->addRow(InlineKeyboardButton::make('❌ Отменить публикацию объявления', callback_data: 'cancel@handleConfirmCancelEstate')));
    }

    public function none(Nutgram $bot): void
    {
        $this->end();
    }
}
