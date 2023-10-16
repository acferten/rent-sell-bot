<?php

namespace Domain\Estate\Menu;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstateType;
use Domain\Shared\Models\Actor\User;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardRemove;

class CreateEstateSecondStep extends InlineMenu
{
    public Estate $estate;
    public EstateData $data;

    public function start(Nutgram $bot): void
    {
        $bot->sendMessage(
            text: "<b>Шаг 2 из 3</b>
Отправьте геолокацию вашего объекта.",
            parse_mode: 'html'
        );

        $this->next('location');
    }

    public function location(Nutgram $bot): void
    {
        $location = $bot->message()->location;

        $this->estate = Estate::where(['user_id' => $bot->userId()])
            ->latest()->first();

        $this->estate->update([
            'latitude' => $location->latitude,
            'longitude' => $location->longitude
        ]);;

        $bot->sendMessage('Локация добавлена к объекту.');

        $bot->sendMessage(
            text: "<b>Шаг 3 из 3</b>
Отправьте ваши контактные данные Telegram.",
            parse_mode: 'html',
            reply_markup: ReplyKeyboardMarkup::make(resize_keyboard: true, one_time_keyboard: true)->addRow(
                KeyboardButton::make('Поделиться контактными данными 📞', request_contact: true)
            ),
        );

        $this->next('contact');
    }

    public function contact(Nutgram $bot): void
    {
        $this->data = EstateData::from($this->estate);
        $estate_type = EstateType::where(['id' => $this->data->house_type_id])->first()->title;
        $periods = implode(', ', $this->estate->prices->map(fn($price) => $price->period)->toArray());

        $preview = "Превью:\n" .
            "<b>Сделка:</b> {$this->data->deal_type->value}\n" .
            "<b>Количество спален</b>: {$this->data->bedrooms}\n" .
            "<b>Количество ванных комнат</b>: {$this->data->bathrooms}\n" .
            "<b>Количество кондиционеров</b>: {$this->data->conditioners}\n" .
            "<b>Включено в стоимость</b>: {$this->data->includes}\n" .
            "<b>Тип недвижимости:</b>:  {$estate_type}\n" .
            "<b>Описание:</b> {$this->data->description}\n";

        $preview .= $this->data->deal_type == DealTypes::rent ? "<b>Период аренды:</b> {$periods}\n<b>Цена за весь период</b>: {$this->data->period_price}\n"
            : "<b>Цена:</b> {$this->data->price}\n";

        Log::debug($preview);

        User::where(['id' => $bot->userId()])
            ->first()
            ->update([
                'phone' => $bot->message()->contact->phone_number
            ]);

        $bot->sendMessage('Контактные данные сохранены.',
            reply_markup: ReplyKeyboardRemove::make(true));

        $this->clearButtons()
            ->menuText($preview, ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('Все верно, перейти к оплате ✅', callback_data: 'payment@handlePayment'))
//            ->addButtonRow(InlineKeyboardButton::make('Изменить данные первого шага ✍️', callback_data: 'changeEstate@handleChangeFirstStep'))
//            ->addButtonRow(InlineKeyboardButton::make('Изменить локацию объекта ✍️', callback_data: 'changeLocation@handleChangeLocation'))
//            ->addButtonRow(InlineKeyboardButton::make('Просмотр прикрепленных изображений 👀', callback_data: 'images@handleViewImages'))
//            ->addButtonRow(InlineKeyboardButton::make('Отменить публикацию объявления ❌', callback_data: 'cancel@handleCancelEstate'))
            ->showMenu();
    }

    public function handlePayment(Nutgram $bot): void
    {
        $this->clearButtons()
            ->menuText("<b>Выбор тарифа</b>\n\nОпределите на какой период вы бы хотели разместить объявление об аренде вашего объекта.\nОбратите внимание, размещая на месяц вы экономите 50%.\n\nПрайс\nНа 5 дней - 10$\nНа 30 дней - 30$\n\nВыберите на какой срок вы бы хотели разместить объявление?",
                ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('На 5 дней', callback_data: '5days@handlePaymentPlan'))
            ->addButtonRow(InlineKeyboardButton::make('На 30 дней', callback_data: '30days@handlePaymentPlan'))
            ->showMenu();
    }

    public function handlePaymentPlan(Nutgram $bot): void
    {
        if ($bot->callbackQuery()->data == '5days') {
            $this->clearButtons()
                ->menuText("<b>Вы выбрали размещение на 5 дней.</b>\n\nСтоимость размещения 10$\n\nВы можете оплатить двумя способами:\n\nПеревод на карту Тинькофф в рублях. Сумма перевода ЧЧЧ рублей. (делаем формулу для конвертации)\n\nПеревод на индонезийскую карту банка BRI в рупиях. Сумма перевода ЯЯЯ рупий. (делаем формулу для конвертации)\n\nКак вам удобнее оплатить?",
                    ['parse_mode' => 'html'])
                ->addButtonRow(InlineKeyboardButton::make('На карту Тинькофф в рублях', callback_data: 'tinkoff5@handlePaymentBank'))
                ->addButtonRow(InlineKeyboardButton::make('На индонезийскую карту в рупиях', callback_data: 'indonesia5@handlePaymentBank'))
                ->showMenu();
        } else if ($bot->callbackQuery()->data == '30days') {
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
                ->menuText("Вот данные для перевода на карту Тинькофф в рублях.\n\nПосле того как переведёте, пришлите, пожалуйста, чек об оплате в чат менеджеру.\n\n2200 7007 7932 1818\n\nOlga G.\n\nСумма для перевода 1000 рублей.",
                    ['parse_mode' => 'html'])->showMenu();
        } else if ($bot->callbackQuery()->data == 'tinkoff30') {
            $this->clearButtons()
                ->menuText("Вот данные для перевода на карту Тинькофф в рублях.\n\nПосле того как переведёте, пришлите, пожалуйста, чек об оплате в чат менеджеру.\n\n2200 7007 7932 1818\n\nOlga G.\n\nСумма для перевода 3000 рублей.",
                    ['parse_mode' => 'html'])->showMenu();
        } else if ($bot->callbackQuery()->data == 'indonesia5') {
            $this->clearButtons()
                ->menuText("Вот данные для перевода на карту индонезийского банка BRI в рупиях.\n\nПосле того как переведёте, пришлите, пожалуйста, чек об оплате в чат менеджеру.\n\n4628 0100 4036 508\n\nAnak Agung Gede Adi Semara\n\nСумма для перевода много рупий.",
                    ['parse_mode' => 'html'])->showMenu();
        } else if ($bot->callbackQuery()->data == 'indonesia30') {
            $this->clearButtons()
                ->menuText("Вот данные для перевода на карту индонезийского банка BRI в рупиях.\n\nПосле того как переведёте, пришлите, пожалуйста, чек об оплате в чат менеджеру.\n\n4628 0100 4036 508\n\nAnak Agung Gede Adi Semara\n\nСумма для перевода много30 рупий.",
                    ['parse_mode' => 'html'])->showMenu();
        }

        $this->next('getPaymentCheque');
    }

    public function getPaymentCheque(Nutgram $bot): void
    {
        $bot->sendMessage('test', '-1001875753187');
        $bot->forwardMessage('-1001875753187', $bot->chatId(), $bot->message()->message_id);
    }

    public function none(Nutgram $bot)
    {
        $this->end();
    }
}
