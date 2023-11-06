<?php

namespace Domain\Estate\Menu;

use Domain\Estate\Models\Estate;
use Domain\Estate\ViewModels\GetEstateViewModel;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class ReportEstateMenu extends InlineMenu
{
    public Estate $estate;

    public function start(Nutgram $bot)
    {

        $this->menuText('Пожалуйста, выберите причину', ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('Объявление закрыто', callback_data: 'fg@reportReason'))
            ->addButtonRow(InlineKeyboardButton::make('Неверная цена', callback_data: 'fdg@reportReason'))
            ->addButtonRow(InlineKeyboardButton::make('Неверное описание, фото', callback_data: 'gfdgdf@reportReason'))
            ->addButtonRow(InlineKeyboardButton::make('Неверный адрес', callback_data: 'sdgsd@reportReason'))
            ->addButtonRow(InlineKeyboardButton::make('Продавец не отвечает', callback_data: 'dgdfgd@reportReason'))
            ->addButtonRow(InlineKeyboardButton::make('Мошенник', callback_data: 'dfgdf@reportReason'))
            ->showMenu();
    }

    public function reportReason(Nutgram $bot): void
    {
        $bot->sendMessage("<b>😡 Новая жалоба на объявление</b>\n" .
            GetEstateViewModel::get($this->estate) .
            "\nОт: {$bot->user()->username}, {$bot->user()->first_name}" .
            "\nПричина: {$bot->callbackQuery()->data}",
            '-1001875753187', parse_mode: 'html', disable_notification: true);

        $this->closeMenu();
    }
}
