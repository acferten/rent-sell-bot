<?php

namespace Domain\Estate\Menu;

use Carbon\Carbon;
use Domain\Estate\Enums\EstateCallbacks;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use Domain\Estate\ViewModels\AdminEstatePreviewViewModel;
use Domain\Shared\Enums\MessageText;
use Domain\Shared\Models\Actor\User;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class EstatePaymentMenu extends InlineMenu
{
    public Estate $estate;

    public function start(Nutgram $bot): void
    {
        $this->estate = Estate::find($bot->getUserData('estate_id', $bot->userId()));

        $bot->deleteMessage($bot->userId(), $bot->getUserData('preview_message_id'));

        $this->clearButtons()
            ->menuText("<b>Выбор тарифа</b>\n\nОпределите на какой период вы бы хотели разместить объявление об аренде вашего объекта.\nОбратите внимание, размещая на месяц вы экономите 50%.\n\nПрайс\nНа 5 дней - 10$\nНа 30 дней - 30$\n\nВыберите на какой срок вы бы хотели разместить объявление?",
                ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('На 5 дней', callback_data: '5days@handlePaymentPlan'))
            ->addButtonRow(InlineKeyboardButton::make('На 30 дней', callback_data: '30days@handlePaymentPlan'))
            ->addButtonRow(InlineKeyboardButton::make('Отмена публикации', callback_data: 'cancel publish'))
            ->addButtonRow(InlineKeyboardButton::make(EstateCallbacks::CallManager->value, url: MessageText::ManagerUrl->value))
            ->showMenu();
    }

    public function handlePaymentPlan(Nutgram $bot): void
    {
        if ($bot->callbackQuery()->data == '5days') {

            $this->estate->update([
                'end_date' => Carbon::now()->addDays(5)
            ]);

            $this->clearButtons()
                ->menuText("<b>Вы выбрали размещение на 5 дней.</b>\n\nСтоимость размещения 10$\n\nВы можете оплатить двумя способами:\n\nПеревод на карту Тинькофф в рублях. Сумма перевода ЧЧЧ рублей. (делаем формулу для конвертации)\n\nПеревод на индонезийскую карту банка BRI в рупиях. Сумма перевода ЯЯЯ рупий. (делаем формулу для конвертации)\n\nКак вам удобнее оплатить?",
                    ['parse_mode' => 'html'])
                ->addButtonRow(InlineKeyboardButton::make('На карту Тинькофф в рублях', callback_data: 'tinkoff5@handlePaymentBank'))
                ->addButtonRow(InlineKeyboardButton::make('На индонезийскую карту в рупиях', callback_data: 'indonesia5@handlePaymentBank'))
                ->showMenu();
        } else if ($bot->callbackQuery()->data == '30days') {

            $this->estate->update([
                'end_date' => Carbon::now()->addDays(30)
            ]);

            $this->clearButtons()
                ->menuText("<b>Вы выбрали размещение на 30 дней.</b>\n\nСтоимость размещения 30$\n\nВы можете оплатить двумя способами:\n\nПеревод на карту Тинькофф в рублях. Сумма перевода ЧЧЧ рублей. (делаем формулу для конвертации)\n\nПеревод на индонезийскую карту банка BRI в рупиях. Сумма перевода ЯЯЯ рупий. (делаем формулу для конвертации)\n\nКак вам удобнее оплатить?",
                    ['parse_mode' => 'html'])
                ->addButtonRow(InlineKeyboardButton::make('На карту Тинькофф в рублях', callback_data: 'tinkoff30@handlePaymentBank'))
                ->addButtonRow(InlineKeyboardButton::make('На индонезийскую карту в рупиях', callback_data: 'indonesia30@handlePaymentBank'))
                ->showMenu();
        }

    }

    public function handlePaymentBank(Nutgram $bot): void
    {
        if ($bot->callbackQuery()->data == 'tinkoff5') {
            $this->clearButtons()
                ->menuText("Вот данные для перевода на карту Тинькофф в рублях.\n\nПосле того как переведёте, пришлите, пожалуйста, чек об оплате боту в формате изображения.\n\n2200 7007 7932 1818\n\nOlga G.\n\nСумма для перевода 1000 рублей.",
                    ['parse_mode' => 'html'])->showMenu();
        } else if ($bot->callbackQuery()->data == 'tinkoff30') {
            $this->clearButtons()
                ->menuText("Вот данные для перевода на карту Тинькофф в рублях.\n\nПосле того как переведёте, пришлите, пожалуйста, чек об оплате боту в формате изображения.\n\n2200 7007 7932 1818\n\nOlga G.\n\nСумма для перевода 3000 рублей.",
                    ['parse_mode' => 'html'])->showMenu();
        } else if ($bot->callbackQuery()->data == 'indonesia5') {
            $this->clearButtons()
                ->menuText("Вот данные для перевода на карту индонезийского банка BRI в рупиях.\n\nПосле того как переведёте, пришлите, пожалуйста, чек об оплате боту в формате изображения.\n\n4628 0100 4036 508\n\nAnak Agung Gede Adi Semara\n\nСумма для перевода много рупий.",
                    ['parse_mode' => 'html'])->showMenu();
        } else if ($bot->callbackQuery()->data == 'indonesia30') {
            $this->clearButtons()
                ->menuText("Вот данные для перевода на карту индонезийского банка BRI в рупиях.\n\nПосле того как переведёте, пришлите, пожалуйста, чек об оплате боту в формате изображения.\n\n4628 0100 4036 508\n\nAnak Agung Gede Adi Semara\n\nСумма для перевода много30 рупий.",
                    ['parse_mode' => 'html'])->showMenu();
        }

        $this->next('getPaymentCheque');
    }

    public function getPaymentCheque(Nutgram $bot): void
    {
        $photoId = $bot->message()->photo[0]->file_id;

        $this->estate->update([
            'status' => EstateStatus::pending->value
        ]);
        $preview = AdminEstatePreviewViewModel::get($this->estate);
        $user_url = 'https://t.me/' . $this->estate->user->username;

        $bot->sendPhoto($photoId, '-1001875753187', caption: $preview,
            parse_mode: 'html',
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make('✅ Одобрить', callback_data: "approve {$this->estate->id}"))
                ->addRow(InlineKeyboardButton::make('👀 Посмотреть подробнее',
                    url: env('NGROK_SERVER') . "/estate/{$this->estate->id}"))
                ->addRow(InlineKeyboardButton::make('✍️ Написать человеку', url: $user_url))
                ->addRow(InlineKeyboardButton::make('❌ Отклонить', callback_data: "decline {$this->estate->id}"))
        );
        $bot->deleteUserData('estate_id', $this->estate->user_id);
        $this->closeMenu();
        $this->end();

    }
}
