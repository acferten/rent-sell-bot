<?php

namespace Domain\Estate\Menu;

use Domain\Estate\Enums\EstateCallbacks;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Messages\AdminChatEstateCardMessage;
use Domain\Estate\Models\Estate;
use Domain\Estate\ViewModels\AdminEstatePreviewViewModel;
use Domain\Shared\Enums\MessageText;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;

class EstatePaymentMenu extends InlineMenu
{
    public Estate $estate;

    public function start(Nutgram $bot): void
    {
        $this->estate = Estate::find($bot->getUserData('estate_id', $bot->userId()));

        AdminChatEstateCardMessage::send($this->estate);

        $bot->deleteMessage($bot->userId(), $bot->getUserData('preview_message_id'));

        $this->clearButtons()
            ->menuText("🌟 Прекрасно! Вам осталось оплатить объявление и его увидят все пользователи GetKeysBot.

💸 Стоимость размещение одного объявления на 30 дней:
300.000 IDR

Как вам удобнее оплатить?
",
                ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('💰 На карту Тинькофф в рублях', callback_data: 'tinkoff@handlePaymentBank'))
            ->addButtonRow(InlineKeyboardButton::make('💸 На индонезийскую карту в рупиях', callback_data: 'indonesia@handlePaymentBank'))
            ->addButtonRow(InlineKeyboardButton::make('🙅‍♂️ Отменить размещение ', callback_data: 'cancel publish'))
            ->addButtonRow(InlineKeyboardButton::make(EstateCallbacks::CallManager->value, url: MessageText::ManagerUrl->value))
            ->showMenu();
    }


    public function handlePaymentBank(Nutgram $bot): void
    {
        $rub = 300000 / 1000 * 6.2;

        if ($bot->callbackQuery()->data == 'tinkoff') {
            $this->clearButtons()
                ->menuText("💳 Вот данные для перевода на карту Тинькофф в рублях:

         <b>2200 7007 7932 1818
            Olga G. </b>

Сумма для перевода {$rub} рублей.

 🧾 После того как переведёте, нажмите Отправить чек и прикрепите скриншот.
", ['parse_mode' => 'html'])->showMenu();

        } else if ($bot->callbackQuery()->data == 'indonesia') {
            $this->clearButtons()
                ->menuText("💳 Вот данные для перевода на карту индонезийского банка BRI в рупиях:
            <b>4628 0100 4036 508
               Anak Agung Gede Adi Semara</b>

Сумма для перевода 300.000 IDR

 🧾 После того как переведёте, нажмите Отправить чек и прикрепите скриншот.
", ['parse_mode' => 'html'])->showMenu();
        }

        $this->next('getPaymentCheque');
    }

    public function getPaymentCheque(Nutgram $bot): void
    {
        $photoId = $bot->message()->photo[0]->file_id;

        $this->estate->update([
            'status' => EstateStatus::pending->value
        ]);

        $bot->deleteUserData('estate_id', $this->estate->user_id);
        $this->closeMenu();
        $this->end();

    }
}
