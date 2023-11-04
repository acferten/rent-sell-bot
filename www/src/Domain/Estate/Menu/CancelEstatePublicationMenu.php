<?php

namespace Domain\Estate\Menu;

use Domain\Estate\Enums\CancelReasons;
use Domain\Estate\Models\Estate;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class CancelEstatePublicationMenu extends InlineMenu
{
    public Estate $estate;

    public function start(Nutgram $bot): void
    {
        $this->clearButtons()
            ->menuText("<b>Подтверждение удаления</b>\n\n Вы действительно хотите удалить черновик Вашего объявления?",
                ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('💣 Удалить', callback_data: 'cancel@askReason'))
            ->addButtonRow(InlineKeyboardButton::make('◀️ Отмена', callback_data: 'preview@cancel'))
            ->showMenu();

        $this->estate = Estate::first($bot->getUserData('estate_id', $bot->userId()));
    }

    public function askReason(Nutgram $bot): void
    {
        $this->clearButtons()
            ->menuText("<b>Пожалуйста, укажите причину отмены публикации</b>",
                ['parse_mode' => 'html']);

        foreach (CancelReasons::cases() as $reason) {
            $this->addButtonRow(InlineKeyboardButton::make(
                $reason->value,
                callback_data: $reason->name . '@delete'));
        }
        $this->showMenu();
    }

    public function delete(Nutgram $bot): void
    {
        $bot->sendMessage("<b>Пользователь не дошел до финального шага создания объекта</b>
Причина: {$bot->callbackQuery()->data}",
            '-1001875753187',
            parse_mode: 'html',
        );

        $this->estate->delete();
        $bot->deleteUserData('estate_id', $bot->userId());

        $bot->sendMessage('Публикация успешно удалена.', $this->estate->user_id);
        $this->closeMenu();
        $this->end();
    }

    public function cancel(Nutgram $bot): void
    {
        $this->closeMenu();
    }
}
