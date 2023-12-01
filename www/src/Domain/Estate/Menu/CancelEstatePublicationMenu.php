<?php

namespace Domain\Estate\Menu;

use Domain\Estate\Actions\SendPreviewMessageAction;
use Domain\Estate\Enums\CancelReasons;
use Domain\Estate\Enums\EstateCallbacks;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Messages\AdminChatEstateCardMessage;
use Domain\Estate\Models\Estate;
use Domain\Shared\Enums\MessageText;
use Illuminate\Support\Facades\Storage;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;

class CancelEstatePublicationMenu extends InlineMenu
{
    public Estate $estate;

    public function start(Nutgram $bot): void
    {
        $this->estate = Estate::find($bot->getUserData('estate_id', $bot->userId()));

        $this->clearButtons()
            ->menuText("😔 Вы действительно хотите удалить черновик Вашего объявления?
Если возникли какие-то вопросы, то напишите нашему менеджеру.
", ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('💣 Удалить черновик', callback_data: 'cancel@delete'))
            ->addButtonRow(InlineKeyboardButton::make(EstateCallbacks::CallManager->value, url: MessageText::ManagerUrl->value))
            ->addButtonRow(InlineKeyboardButton::make('◀️ Шаг назад', callback_data: 'preview@cancel'))
            ->showMenu();
    }

    public function delete(Nutgram $bot): void
    {
        AdminChatEstateCardMessage::send($this->estate);
        $this->estate->update(['status' => EstateStatus::deletedDraft->value]);

        if ($bot->getUserData('preview_message_id')) {
            $bot->deleteMessage($bot->userId(), $bot->getUserData('preview_message_id'));
        }
        $bot->deleteUserData('estate_id');
        $bot->deleteUserData('preview_message_id');

        $bot->sendMessage('😇 Мы всегда готовы помочь сдать ваше жильё.
🔑 Теперь вы знаете, что разместить объявление на <b>GetKeysBot</b> быстро и просто.
Здесь также легко найти вариант для жилья.
', $this->estate->user_id, parse_mode: 'html');
        $this->closeMenu();
        $this->end();
    }

    public function cancel(Nutgram $bot): void
    {
        if ($bot->getUserData('preview_message_id')) {
            $bot->deleteMessage($bot->userId(), $bot->getUserData('preview_message_id'));
        }
        SendPreviewMessageAction::execute($bot, $this->estate->id);
        $this->closeMenu();
    }
}
