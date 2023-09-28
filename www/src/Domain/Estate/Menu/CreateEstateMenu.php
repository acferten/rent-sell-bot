<?php

namespace Domain\Estate\Menu;

use Domain\Estate\Enums\CreateEstateText;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class CreateEstateMenu extends InlineMenu
{
    protected ?string $step = 'askDealType';

    public $cupSize = '';

    public function askDealType(Nutgram $bot): void
    {
        $this->clearButtons()
            ->menuText(CreateEstateText::DealType->value)
            ->addButtonRow(InlineKeyboardButton::make('Сдать', callback_data: 'createEstate-DealType-Rent@handleType'))
            ->addButtonRow(InlineKeyboardButton::make('Продать', callback_data: 'createEstate-DealType-Sell@handleType'))
            ->orNext('none')
            ->showMenu();
    }

    public function handleType(Nutgram $bot): void
    {
        $dealType = $bot->callbackQuery()->data;

        $this->clearButtons()
            ->menuText('llfdg;dfdldf')
            ->addButtonRow(InlineKeyboardButton::make('fsdfdfsd', callback_data: 'crea@recap'))
            ->addButtonRow(InlineKeyboardButton::make('Назад', callback_data: 'crea@recap'))
            ->showMenu();
    }

    public function recap(Nutgram $bot)
    {
        $flavors = $bot->message()->text;
        $bot->sendMessage("You want an $this->cupSize cup with this flavors: $flavors");
        $this->end();
    }
}
