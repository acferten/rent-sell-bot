<?php

namespace Domain\Estate\Menu;

use Domain\Estate\Enums\CreateEstateText;
use Domain\Estate\Enums\DealTypes;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class CreateEstateMenu extends InlineMenu
{
    protected ?string $step = 'askDealType';
    public string $description;
    public string $bathrooms;
    public string $bedrooms;
    public string $conditioners;
    public string $views;
    public string $chattings;
    public string $video_review;
    public string $status;
    public string $deal_type;

    public function askDealType(Nutgram $bot): void
    {
        $this->clearButtons()
            ->menuText(CreateEstateText::DealType->value);

        foreach (DealTypes::cases() as $deal) {
            $this->addButtonRow(InlineKeyboardButton::make($deal->value, callback_data: 'createEstate-DealType-Sell@handleType'));
        }

        $this->orNext('none')
            ->showMenu();
    }


    public function handleType(Nutgram $bot): void
    {
        $dealType = $bot->callbackQuery()->data;

        $this->clearButtons()
            ->menuText('aaaaaa')
            ->addButtonRow(InlineKeyboardButton::make('bbbb', callback_data: 'crea@recap'))
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
