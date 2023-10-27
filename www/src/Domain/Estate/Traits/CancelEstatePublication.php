<?php

namespace Domain\Estate\Traits;


use Domain\Estate\Models\Estate;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

trait CancelEstatePublication
{
    public function handleConfirmCancelEstate(Nutgram $bot): void
    {
        $this->clearButtons()
            ->menuText("<b>Подтверждение удаления</b>\n\n Вы действительно хотите удалить черновик Вашего объявления?",
                ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('💣 Удалить', callback_data: 'cancel@handleCancelEstate'))
            ->addButtonRow(InlineKeyboardButton::make('◀️ Отмена', callback_data: 'preview@getPreviewLayout'))
            ->showMenu();
    }

    public function handleCancelEstate(Nutgram $bot): void
    {
        Estate::where(['user_id' => $bot->userId()])
            ->latest()->first()->delete();
        $this->clearButtons();
        $bot->sendMessage('Публикация успешно удалена');
        $this->closeMenu();
        $this->end();
    }
}