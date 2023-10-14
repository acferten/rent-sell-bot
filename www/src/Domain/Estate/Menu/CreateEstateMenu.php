<?php

namespace Domain\Estate\Menu;

use Domain\Estate\Enums\CreateEstateText;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class CreateEstateMenu extends InlineMenu
{
    public function start(Nutgram $bot): void
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
        $this->end();
    }
}
