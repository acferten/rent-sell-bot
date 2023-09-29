<?php

namespace Domain\Shared\Menu;

use Domain\Estate\Enums\EstateCallbacks;
use Domain\Shared\Enums\MessageText;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class StartMenu extends InlineMenu
{
    public function menuLayout(
        MessageText     $text,
        EstateCallbacks $button1,
        EstateCallbacks $button2,
        string          $funcName): self
    {
        return $this->menuText($text->value)
            ->clearButtons()->addButtonRow(InlineKeyboardButton::make(
                $button1->value,
                callback_data: "{$button1->name}@{$funcName}")
            )
            ->addButtonRow(InlineKeyboardButton::make(
                $button2->value,
                callback_data: "{$button2->name}@{$funcName}")
            );
    }

    public function start(Nutgram $bot): void
    {
        $this->menuLayout(
            MessageText::StartCommandText,
            EstateCallbacks::StartCreateRentEstate,
            EstateCallbacks::StartGetEstates,
            'handleStartChoice'
        )->showMenu();
    }

    public function handleStartChoice(Nutgram $bot): void
    {
        if ($bot->callbackQuery()->data == EstateCallbacks::StartCreateRentEstate->name) {
            $this->menuLayout(
                MessageText::StartCommandText,
                EstateCallbacks::CreateRentEstate,
                EstateCallbacks::CallManager,
                'handleCreateEstateChoice'
            )->showMenu();
        } else {
            $this->menuLayout(
                MessageText::GetEstatesText,
                EstateCallbacks::GetEstates,
                EstateCallbacks::GetFilteredEstates,
                'handleGetEstatesChoice'
            )->showMenu();
        }
    }

    public function handleCreateEstateChoice(Nutgram $bot): void
    {

    }

    public function handleGetEstatesChoice(Nutgram $bot): void
    {

    }


    public function none(Nutgram $bot): void
    {
        $bot->sendMessage('Bye!');
        $this->end();
    }
}
