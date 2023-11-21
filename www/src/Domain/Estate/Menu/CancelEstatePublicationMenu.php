<?php

namespace Domain\Estate\Menu;

use Domain\Estate\Actions\SendPreviewMessageAction;
use Domain\Estate\Enums\CancelReasons;
use Domain\Estate\Enums\EstateCallbacks;
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
            ->menuText("<b>Подтверждение удаления</b>\n\n 😔 Вы действительно хотите удалить черновик Вашего объявления? Если возникли какие-то трудности при создании, Вы можете связаться с нашим менеджером.",
                ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('💣 Удалить', callback_data: 'cancel@askReason'))
            ->addButtonRow(InlineKeyboardButton::make(EstateCallbacks::CallManager->value, url: MessageText::ManagerUrl->value))
            ->addButtonRow(InlineKeyboardButton::make('◀️ Отмена', callback_data: 'preview@cancel'))
            ->showMenu();
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
        $bot->sendMessage("<b>😞 Пользователь не дошел до финального шага создания объекта</b>
<b>Причина:</b> {$bot->callbackQuery()->data}
<b>Пользователь:</b> {$this->estate->user->username}, {$this->estate->user->phone}",
            '-1001875753187',
            parse_mode: 'html',
            disable_notification: true
        );

        $this->estate->delete();

        // clear storage files
        $this->estate->photos
            ->each(fn($photo) => Storage::disk('photos')->delete($photo->photo));
        Storage::disk('photos')->delete($this->estate->main_photo);
        if ($this->estate->video) {
            Storage::disk('photos')->delete($this->estate->video);
        }

        if ($bot->getUserData('preview_message_id')) {
            $bot->deleteMessage($bot->userId(), $bot->getUserData('preview_message_id'));
        }
        $bot->deleteUserData('estate_id');
        $bot->deleteUserData('preview_message_id');

        $bot->sendMessage('Публикация успешно удалена.', $this->estate->user_id);
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
