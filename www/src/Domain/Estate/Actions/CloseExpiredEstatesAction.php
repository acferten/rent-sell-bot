<?php

namespace Domain\Estate\Actions;

use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use Domain\Shared\Enums\MessageText;
use Illuminate\Support\Facades\DB;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class CloseExpiredEstatesAction
{
    public function __invoke(): void
    {
        DB::transaction(function () {
            $estates = Estate::where('status', '!=', EstateStatus::closed->value)
                ->whereDate('end_date', '<', date("Y-m-d"));
            $estates->update(['status' => EstateStatus::closed]);

            $estates->get()->each(function ($estate) {
                Telegram::sendMessage("Период размещения объекта закончился. Теперь соискатели жилья не видят его при поиске.\nДля продолжения размещения Вы можете связаться с менеджером.",
                    $estate->user_id,
                    reply_markup: InlineKeyboardMarkup::make()
                        ->addRow(InlineKeyboardButton::make('Написать менеджеру', url: MessageText::ManagerUrl->value)));
            });
        });
    }
}
