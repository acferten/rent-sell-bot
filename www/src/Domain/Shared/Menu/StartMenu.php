<?php

namespace Domain\Shared\Menu;

use Domain\Estate\Enums\CreateEstateText;
use Domain\Estate\Enums\EstateCallbacks;
use Domain\Estate\Menu\CreateEstateMenu;
use Domain\Estate\Menu\GetEstatesConversation;
use Domain\Estate\Menu\GetEstatesMenu;
use Domain\Shared\Enums\MessageText;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class StartMenu extends InlineMenu
{

    public function start(Nutgram $bot): void
    {
        $this->menuLayout(
            MessageText::StartCommandText,
            EstateCallbacks::StartCreateEstate,
            EstateCallbacks::StartGetEstates,
            'handleStartChoice'
        )->showMenu();
    }

    public function handleStartChoice(Nutgram $bot): void
    {
        if ($bot->callbackQuery()->data == EstateCallbacks::StartCreateEstate->name) {
            $this->menuText(MessageText::StartCommandText->value)
                ->clearButtons()->addButtonRow(InlineKeyboardButton::make(
                    EstateCallbacks::CreateEstate->value,
                    callback_data: EstateCallbacks::CreateEstate->name . '@handleCreateEstateChoice'
                ))
                ->addButtonRow(InlineKeyboardButton::make(
                    EstateCallbacks::CallManager->value,
                    url: MessageText::ManagerUrl->value
                ))
                ->backButton()
                ->showMenu();
        } else {
            $this->menuText(MessageText::GetEstatesText->value)
                ->clearButtons()
                ->addButtonRow(InlineKeyboardButton::make(
                    EstateCallbacks::GetEstates->value,
                    callback_data: "getEstates@handleGetEstatesChoice")
                )
                ->addButtonRow(InlineKeyboardButton::make(
                    EstateCallbacks::GetFilteredEstates->value,
                    web_app: new WebAppInfo(CreateEstateText::EstateUrl->value . "/filters"))
                )->backButton()->orNext('none')->showMenu();
        }
    }

    public function handleCreateEstateChoice(Nutgram $bot): void
    {
        $this->closeMenu();
        $bot->onCallbackQueryData(EstateCallbacks::CreateEstate->name, CreateEstateMenu::begin($bot));
    }

    public function handleGetEstatesChoice(Nutgram $bot): void
    {
        $this->closeMenu();
        $bot->onCallbackQueryData("getEstates", GetEstatesConversation::begin($bot));
    }

    public function menuLayout(MessageText $text, EstateCallbacks $button1, EstateCallbacks $button2, string $funcName): self
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

    public function handleBack(Nutgram $bot): void
    {
        $this->start($bot);
    }
}
