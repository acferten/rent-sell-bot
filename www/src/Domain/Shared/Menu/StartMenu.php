<?php

namespace Domain\Shared\Menu;

use Domain\Estate\Conversations\GetFilteredEstatesConversation;
use Domain\Estate\Enums\EstateCallbacks;
use Domain\Shared\Enums\MessageText;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class StartMenu extends InlineMenu
{

    public function start(Nutgram $bot): void
    {
        $this->menuText(MessageText::StartCommandText->value)
            ->clearButtons()
            ->addButtonRow(InlineKeyboardButton::make(
                EstateCallbacks::StartCreateEstate->value,
                callback_data: EstateCallbacks::StartCreateEstate->name . "@startCreateEstateChoice")
            )
            ->addButtonRow(InlineKeyboardButton::make(
                EstateCallbacks::StartGetEstates->value,
                callback_data: EstateCallbacks::StartGetEstates->name . "@startGetEstatesChoice")
            )->orNext('none')->showMenu();
    }

    public function startCreateEstateChoice(Nutgram $bot): void
    {
        $this->menuText(MessageText::CreateRentText->value, ['parse_mode' => 'html'])
            ->clearButtons()->addButtonRow(InlineKeyboardButton::make(
                EstateCallbacks::CreateEstate->value,
                callback_data: EstateCallbacks::CreateEstate->name . '@createEstateChoice'
            ))
            ->addButtonRow(InlineKeyboardButton::make(
                EstateCallbacks::CallManager->value,
                url: MessageText::ManagerUrl->value
            ))->orNext('none')
            ->backButton()
            ->showMenu();
    }

    public function createEstateChoice(Nutgram $bot): void
    {
        $this->clearButtons()
            ->menuText("<b>Шаг 1 из 3</b>
Заполните данные об объекте, который Вы хотите сдать в долгосрочную аренду. Это займёт не более 5 минут.",
                ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make(
                '✍️ Заполнить форму',
                web_app: new WebAppInfo(env('NGROK_SERVER') . "/estates/create"))
            )->addButtonRow(InlineKeyboardButton::make(
                EstateCallbacks::CallManager->value,
                url: MessageText::ManagerUrl->value
            ))->orNext('none')
            ->backButton()
            ->showMenu();
    }

    public function startGetEstatesChoice(): void
    {
        $this->menuText(MessageText::GetEstatesText->value, ['parse_mode' => 'html'])
            ->clearButtons()
            ->addButtonRow(InlineKeyboardButton::make(
                EstateCallbacks::GetFilteredEstates->value,
                web_app: new WebAppInfo(env('NGROK_SERVER') . "/estates/filters"))
            )->addButtonRow(InlineKeyboardButton::make(
                EstateCallbacks::CallManager->value,
                url: MessageText::ManagerUrl->value
            ))->backButton()
            ->orNext('none')
            ->showMenu();

    }

    public function getEstatesChoice(Nutgram $bot): void
    {
        $this->closeMenu();
        GetFilteredEstatesConversation::begin($bot);
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

    public function none(): void
    {
        $this->closeMenu();
    }
}
