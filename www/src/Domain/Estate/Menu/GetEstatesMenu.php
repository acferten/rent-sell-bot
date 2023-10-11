<?php

namespace Domain\Estate\Menu;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use Domain\Estate\ViewModels\GetEstatesViewModel;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class GetEstatesMenu extends InlineMenu
{
    private int $page = 1;
    private static int $PER_PAGE = 3;
    public function start(Nutgram $bot): void
    {
        $this->estates = Estate::where('status', EstateStatus::active->value)
            ->limit(self::$PER_PAGE)
            ->offset($this->page * self::$PER_PAGE)
            ->get();
        $this->menuText('Выберите объект недвижимости:');
        $this->clearButtons();
        foreach ($this->estates as $estate) {
            $this->addButtonRow(InlineKeyboardButton::make($estate->shortData(), callback_data: "$estate->id@estate_clicked"));
        }
        $this->addButtonRow(
            InlineKeyboardButton::make('<<', callback_data: 'next@page_clicked'),
            InlineKeyboardButton::make('>>', callback_data: 'prev@page_clicked'),
        );
        $this->showMenu();
    }

    public function estate_clicked(Nutgram $bot)
    {
        $this->menuText(Estate::find($bot->callbackQuery()->data)->fullData())
            ->showMenu();
    }

    public function page_clicked(Nutgram $bot)
    {
        $this->page = $bot->callbackQuery()->data == 'next' ? $this->page + 1 : $this->page - 1;
        $this->start($bot);
    }
}
