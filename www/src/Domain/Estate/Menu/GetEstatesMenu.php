<?php

namespace Domain\Estate\Menu;

use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class GetEstatesMenu extends InlineMenu
{
    public function start(Nutgram $bot): void
    {
        $data = $bot->callbackQuery()->data ?? null;
        $estate = Estate::where('status', EstateStatus::active->value)
            ->where('id', '=', $data ?? 0)
            ->orderBy('id')
            ->limit(1)
            ->first();
        $prevEstate = Estate::where('status', EstateStatus::active->value)
            ->where('id', '<', $estate->id)
            ->orderBy('id', 'desc')
            ->limit(1)
            ->first();
        $nextEstate = Estate::where('status', EstateStatus::active->value)
            ->where('id', '>', $estate->id)
            ->orderBy('id')
            ->limit(1)
            ->first();
        $this->menuText($estate->fullData() ?? 'Объектов нет');
        $this->clearButtons();
        if ($prevEstate && $nextEstate) {
            $this->addButtonRow(
                InlineKeyboardButton::make('<<',
                    callback_data: "{$prevEstate->id}@start"),
                InlineKeyboardButton::make('>>',
                    callback_data: "{$nextEstate->id}@start")
            );
        }
        elseif ($prevEstate) {
            $this->addButtonRow(
                InlineKeyboardButton::make('<<',
                    callback_data: "{$prevEstate->id}@start")
            );
        }
        elseif ($nextEstate) {
            $this->addButtonRow(
                InlineKeyboardButton::make('>>',
                    callback_data: "{$nextEstate->id}@start")
            );
        }
        $this->showMenu();
        $estate->update(['views' => $estate->views + 1]);
    }
}
