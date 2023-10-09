<?php

namespace Domain\Estate\Menu;

use Domain\Estate\Enums\CreateEstateText;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class CreateEstateMenu extends InlineMenu
{
    protected ?string $step = 'firstStep';

    public function firstStep(Nutgram $bot): void
    {
        $this->clearButtons()
            ->menuText(CreateEstateText::FirstStepHeader->value
                . CreateEstateText::FirstStepDescription->value, ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make(
                CreateEstateText::FillEstateFormText->value,
                web_app: new WebAppInfo(CreateEstateText::FillEstateFormUrl->value))
            )->orNext('none')->showMenu();

    }

    public function none(Nutgram $bot)
    {
        $bot->sendMessage('Выберите команду из меню.');
        $this->end();
    }

    //TODO: back button

}
