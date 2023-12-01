<?php

namespace Domain\Estate\Menu;

use Domain\Estate\Enums\EstateCallbacks;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Messages\AdminChatEstateCardMessage;
use Domain\Estate\Models\Estate;
use Domain\Shared\Enums\MessageText;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class EstatePaymentMenu extends InlineMenu
{
    public Estate $estate;

    public function start(Nutgram $bot): void
    {
        $this->estate = Estate::find($bot->getUserData('estate_id', $bot->userId()));

        AdminChatEstateCardMessage::send($this->estate);

        $bot->deleteMessage($bot->userId(), $bot->getUserData('preview_message_id'));

        $this->clearButtons()
            ->menuText("🌟 Прекрасно! Вам осталось оплатить объявление и его увидят все пользователи <b>GetKeysBot</b>.

<b>💸 Стоимость размещение одного объявления на 30 дней:</b>
300.000 IDR

Как вам удобнее оплатить?
",
                ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('💰 На карту Тинькофф в рублях', callback_data: 'tinkoff@handlePaymentBank'))
            ->addButtonRow(InlineKeyboardButton::make('💸 На индонезийскую карту в рупиях', callback_data: 'indonesia@handlePaymentBank'))
            ->addButtonRow(InlineKeyboardButton::make('🙅‍♂️ Отменить размещение ', callback_data: 'cancel publish'))
            ->addButtonRow(InlineKeyboardButton::make(EstateCallbacks::CallManager->value, url: MessageText::ManagerUrl->value))
            ->orNext('none')->showMenu();
    }


    public function handlePaymentBank(Nutgram $bot): void
    {
        $rub = 300000 / 1000 * 6.2;

        if ($bot->callbackQuery()->data == 'tinkoff') {
            $this->clearButtons()
                ->menuText("💳 Вот данные для перевода на карту Тинькофф в рублях:

         <b>2200 7007 7932 1818
            Olga G. </b>

<b>Сумма для перевода {$rub} рублей.</b>

 🧾 После того как переведёте, отправьте боту скриншот о переводе.
", ['parse_mode' => 'html'])->addButtonRow(InlineKeyboardButton::make(EstateCallbacks::CallManager->value, url: MessageText::ManagerUrl->value))
                ->orNext('none')->showMenu();

        } else if ($bot->callbackQuery()->data == 'indonesia') {
            $this->clearButtons()
                ->menuText("💳 Вот данные для перевода на карту индонезийского банка BRI в рупиях:
            <b>4628 0100 4036 508
               Anak Agung Gede Adi Semara</b>

<b>Сумма для перевода 300.000 IDR</b>

 🧾 После того как переведёте, отправьте боту скриншот о переводе.
", ['parse_mode' => 'html'])->addButtonRow(InlineKeyboardButton::make(EstateCallbacks::CallManager->value, url: MessageText::ManagerUrl->value))
                ->orNext('none')->showMenu();
        }

        $this->next('getPaymentCheque');
    }

    public function getPaymentCheque(Nutgram $bot): void
    {
        $fileId = $bot->message()->photo[0]->file_id;

        $bot->sendPhoto(
            $fileId,
            env('ADMIN_CHAT_ID'),
            reply_to_message_id: $this->estate->admin_message_id
        );

        $bot->deleteMessage($bot->userId(), $bot->message()->message_id);

        Estate::withoutEvents(function () {
            $this->estate->update([
                'status' => EstateStatus::pending->value
            ]);
        });

        $this->clearButtons()->menuText('Спасибо! Мы получили чек.
    Модератор проверит ваше объявление в течение одного часа. Модератор может написать вам для уточнения деталей объявления.
    Мы работаем каждый день с 09:00 до 20:00 (по Бали).')
            ->addButtonRow(InlineKeyboardButton::make(EstateCallbacks::CallManager->value, url: MessageText::ManagerUrl->value))
            ->addButtonRow(InlineKeyboardButton::make("💡 Разместить новое", callback_data: 'none@newEstate'))
            ->addButtonRow(InlineKeyboardButton::make("🏡 Мои объявления", callback_data: 'dfsf@myEstates'))
            ->addButtonRow(InlineKeyboardButton::make("👫 Рекомендовать друзьям", callback_data: 'recommend'))
            ->showMenu();

        $bot->deleteUserData('estate_id', $this->estate->user_id);
    }


    public function newEstate(): void
    {
        $this->clearButtons()
            ->menuText("<b>Шаг 1 из 3</b>
Заполните данные об объекте, который Вы хотите сдать в долгосрочную аренду. Это займёт не более 5 минут.",
                ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make(
                '✍️ Заполнить форму',
                web_app: new WebAppInfo(env('NGROK_SERVER') . "/estates/create"))
            )->addButtonRow(InlineKeyboardButton::make(
                EstateCallbacks::CallManager->value,
                url: MessageText::ManagerUrl->value
            ))->orNext('none')
            ->showMenu();
    }

    public function myEstates(Nutgram $bot): void
    {
        $this->closeMenu();
        UserEstatesMenu::begin($bot);
    }

    public function none(Nutgram $bot): void
    {
        $this->closeMenu();
        $bot->sendMessage('Вы отменили размещение объявления.');
    }
}
