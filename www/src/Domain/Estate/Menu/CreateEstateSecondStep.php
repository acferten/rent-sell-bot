<?php

namespace Domain\Estate\Menu;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Models\Estate;
use Domain\Shared\Models\Actor\User;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

class CreateEstateSecondStep extends InlineMenu
{
    public Estate $estate;

    public function start(Nutgram $bot): void
    {
        $bot->sendMessage(
            text: "<b>Шаг 2 из 3</b>
Отправьте геолокацию вашего объекта.",
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

        $bot->sendMessage('Локация добавлена к объекту.');

        $bot->sendMessage(
            text: "<b>Шаг 3 из 3</b>
Отправьте ваши контактные данные Telegram.",
            parse_mode: 'html',
            reply_markup: ReplyKeyboardMarkup::make(resize_keyboard: true, one_time_keyboard: true)->addRow(
                KeyboardButton::make('Поделиться контактными данными 📞', request_contact: true)
            ),
        );

        $this->next('contact');
    }

    public function contact(Nutgram $bot)
    {
        $data = EstateData::from($this->estate);
        $preview = "Превью:\n" .
            "Описание: $data->description\n" .
            "Количество спален: $data->bedrooms\n" .
            "Количество ванных комнат: $data->bathrooms\n" .
            "Количество кондиционеров: $data->conditioners\n" .
            "Включено в стоимость: $data->includes\n" .
            "Цена: $data->price\n";

        User::where(['id' => $bot->userId()])
            ->first()
            ->update([
                'phone' => $bot->message()->contact->phone_number
            ]);

        $bot->sendMessage('Контактные данные сохранены.');

        $bot->sendMessage(
            text: $preview,
            parse_mode: 'html',
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make('Все верно, перейти к оплате ✅', callback_data: 'S'))
                ->addRow(InlineKeyboardButton::make('Изменить данные первого шага ✍️', callback_data: 'M'))
                ->addRow(InlineKeyboardButton::make('Изменить локацию объекта ✍️', callback_data: 'L'))
                ->addRow(InlineKeyboardButton::make('Отменить публикацию объявления ❌', callback_data: 'N'))
        );

        $this->next('preview');
    }

    public function preview(Nutgram $bot)
    {
        $bot->sendMessage($bot->callbackQuery()->data);
        $this->end();
    }


    public function none(Nutgram $bot)
    {
        $this->end();
    }
}
