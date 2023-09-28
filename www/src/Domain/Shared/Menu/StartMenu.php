<?php

namespace Domain\Shared\Menu;

use Domain\Estate\Enums\EstateCallbacks;
use Domain\Estate\Menu\CreateEstateMenu;
use Domain\Shared\Enums\MessageText;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class StartMenu extends InlineMenu
{
    public function start(Nutgram $bot): void
    {
        $this->menuText(MessageText::StartCommandText->value)
            ->addButtonRow(InlineKeyboardButton::make(
                EstateCallbacks::CreateEstate->value,
                callback_data: EstateCallbacks::CreateEstate->name . "@handleStartChoice")
            )
            ->addButtonRow(InlineKeyboardButton::make(
                EstateCallbacks::GetRentEstates->value,
                callback_data: EstateCallbacks::GetRentEstates->name . "@handleStartChoice")
            )->orNext('none')
            ->showMenu();
    }

    public function handleStartChoice(Nutgram $bot): void
    {
        if ($bot->callbackQuery()->data == EstateCallbacks::CreateEstate->name) {
            $this->menuText("текст об условиях размещения и цене")
                ->clearButtons()->addButtonRow(InlineKeyboardButton::make(
                    '🏡 Разместить объект',
                    callback_data: EstateCallbacks::CreateEstate->name . "@handleChoice")
                )
                ->addButtonRow(InlineKeyboardButton::make(
                    '🙋‍ Написать менеджеру',
                    callback_data: EstateCallbacks::GetRentEstates->name . "@handleChoice")
        )->showMenu();
        }
//        $bot->onCallbackQueryData(EstateCallbacks::GetRentEstates->name, CreateEstateMenu::begin($bot));
//        $bot->onCallbackQueryData(EstateCallbacks::GetSellEstates->name, CreateEstateMenu::begin($bot));
    }

    public function handleChoice(Nutgram $bot): void
    {
//        $bot->onCallbackQueryData(EstateCallbacks::CreateEstate->name, CreateEstateMenu::begin($bot));
//        $bot->onCallbackQueryData(EstateCallbacks::GetRentEstates->name, CreateEstateMenu::begin($bot));
//        $bot->onCallbackQueryData(EstateCallbacks::GetSellEstates->name, CreateEstateMenu::begin($bot));
    }

    public function none(Nutgram $bot): void
    {
        $bot->sendMessage('Bye!');
        $this->end();
    }
}
