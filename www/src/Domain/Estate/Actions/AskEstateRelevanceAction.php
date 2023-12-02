<?php

namespace Domain\Estate\Actions;

use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use Domain\Estate\ViewModels\GetEstateViewModel;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class AskEstateRelevanceAction
{
    public function __invoke(): void
    {
        $estates = Estate::where('status', EstateStatus::active->value)->get();

        $estates->each(function ($estate) {
            $preview = GetEstateViewModel::get($estate);
            Telegram::sendMessage("Ваше объявление еще актуально?\n\n {$preview}",
                $estate->user_id, parse_mode: 'html',
                reply_markup: InlineKeyboardMarkup::make()
                    ->addRow(InlineKeyboardButton::make('✅ Актуально', callback_data: "relevant {$estate->id}"))
                    ->addRow(InlineKeyboardButton::make('❎ Закрыть объявление', callback_data: "close {$estate->id}"))
            );
        });
    }
}
