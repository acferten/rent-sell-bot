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
    public function menuLayout(
        MessageText     $text,
        EstateCallbacks $button1,
        EstateCallbacks $button2,
        string          $funcName): self
    {
        return $this->menuText($text->value)
            ->clearButtons()
            ->addButtonRow(InlineKeyboardButton::make(
                $button1->value,
                callback_data: "{$button1->name}@{$funcName}")
            )
            ->addButtonRow(InlineKeyboardButton::make(
                $button2->value,
                callback_data: "{$button2->name}@{$funcName}")
            )->orNext('none');
    }

    public function backButton(): self
    {
        return $this->addButtonRow(InlineKeyboardButton::make(
            EstateCallbacks::GoBack->value,
            callback_data: EstateCallbacks::GoBack->name . "@handleBack"));
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
            $this->menuText(MessageText::StartCommandText->value)
                ->clearButtons()->addButtonRow(InlineKeyboardButton::make(
                    EstateCallbacks::CreateRentEstate->value,
                    callback_data: EstateCallbacks::CreateRentEstate->name . "@handleCreateEstateChoice")
                )
                ->addButtonRow(InlineKeyboardButton::make(
                    EstateCallbacks::CallManager->value,
                    url: MessageText::ManagerUrl->value)
                )
                ->backButton()
                ->showMenu();
        } else {
            $this->menuLayout(
                MessageText::GetEstatesText,
                EstateCallbacks::GetEstates,
                EstateCallbacks::GetFilteredEstates,
                'handleGetEstatesChoice')
                ->backButton()
                ->showMenu();
        }
    }

    public function handleCreateEstateChoice(Nutgram $bot): void
    {
        $bot->onCallbackQueryData(EstateCallbacks::CreateRentEstate->name, CreateEstateMenu::begin($bot));
//        $bot->onCallbackQueryData(EstateCallbacks::GetEstates->name, f);
//        $bot->onCallbackQueryData(EstateCallbacks::GetFilteredEstates->name, f);

//        $this->end();
    }

    public function handleGetEstatesChoice(Nutgram $bot): void
    {

    }


    public function handleBack(Nutgram $bot): void
    {
        $this->start($bot);
    }
}
