<?php

namespace Domain\Estate\Actions;

use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class AskEstateRelevanceAction
{
    public function __invoke()
    {
        $estates = Estate::where('status', EstateStatus::active)->get();

        $estates->each(fn($estate) => Telegram::sendMessage("Ваше объявление еще актуально?{$estate->name}",
            $estate->user_id,
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make('Актуально', callback_data: "relevant {$estate->id}"))
                ->addRow(InlineKeyboardButton::make('Закрыть объявление', callback_data: "close {$estate->id}"))
        ));
    }
}
