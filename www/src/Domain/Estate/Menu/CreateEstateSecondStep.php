<?php

namespace Domain\Estate\Menu;

use Carbon\Carbon;
use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\CreateEstateText;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstateType;
use Domain\Estate\Traits\ChangeEstateLocation;
use Domain\Shared\Models\Actor\User;
use Illuminate\Support\Facades\Http;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardRemove;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class CreateEstateSecondStep extends InlineMenu
{
    use ChangeEstateLocation;

    public Estate $estate;
    public string $preview;

    public function setPreview(): void
    {
        $data = EstateData::from($this->estate);
        $estate_type = EstateType::where(['id' => $data->house_type_id])->first()->title;
        $periods = implode(', ', $this->estate->prices->map(fn($price) => $price->period)->toArray());

        $preview = "Предпросмотр вашего объекта:\n\n" .
            "<b>Сделка:</b> {$data->deal_type->value}\n" .
            "<b>Количество спален:</b> {$data->bedrooms}\n" .
            "<b>Количество ванных комнат:</b> {$data->bathrooms}\n" .
            "<b>Количество кондиционеров:</b> {$data->conditioners}\n" .
            "<b>Включено в стоимость:</b> {$data->includes}\n" .
            "<b>Тип недвижимости:</b>  {$estate_type}\n" .
            "<b>Описание:</b> {$data->description}\n\n" .
            "<b>Страна:</b> {$data->country}\n" .
            "<b>Город:</b> {$data->town}\n" .
            "<b>Район:</b> {$data->district}\n" .
            "<b>Улица:</b> {$data->street}\n" .
            "<b>Дом:</b> {$data->house_number}\n";

        $preview .= $data->deal_type == DealTypes::rent ? "<b>Период аренды:</b> {$periods}\n<b>Цена за весь период:</b> {$data->period_price}\n"
            : "<b>Цена:</b> {$data->price}\n";

        $this->preview = $preview;
    }

    public function setLocationProperties(Nutgram $bot): void
    {
        $locationiq_key = env('LOCATIONIQ_KEY');
        $response = Http::withHeaders([
            "Accept-Language" => "ru",
        ])->get("https://eu1.locationiq.com/v1/reverse.php?key={$locationiq_key}&lat={$this->estate->latitude}&lon={$this->estate->longitude}&format=json")->collect();

        if (array_key_exists('error', $response->toArray())) {
            return;
            $this->start($bot);
        }
        $response = $response->get('address');

        $this->estate->update([
            'country' => $response['country'],
            'town' => $response['city'],
            'district' => $response['city_district'],
            'street' => $response['road'],
        ]);

        if (array_key_exists('house_number', $response)) {
            $this->estate->update([
                'house_number' => $response['house_number'],
            ]);
        }
    }

    public function start(Nutgram $bot): void
    {
        $bot->sendMessage(
            text: "<b>Шаг 2 из 3</b>
Отправьте геолокацию вашего объекта. Для этого перейдите во вкладку прикрепить и отправьте геолокацию боту.",
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

        $this->setLocationProperties($bot);

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
        User::where(['id' => $bot->userId()])
            ->first()
            ->update([
                'phone' => $bot->message()->contact->phone_number
            ]);

        $bot->sendMessage('Контактные данные сохранены.',
            reply_markup: ReplyKeyboardRemove::make(true));

        $this->setPreview();

        $this->clearButtons()
            ->menuText($this->preview, ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('Все верно, перейти к оплате ✅', callback_data: 'payment@handlePayment'))
            ->addButtonRow(InlineKeyboardButton::make('Изменить данные первого шага ✍️', web_app: new WebAppInfo(CreateEstateText::EstateUrl->value . "/{$this->estate->id}/edit")))
            ->addButtonRow(InlineKeyboardButton::make('Изменить локацию объекта ✍️', callback_data: 'changeLocation@handleChangeLocation'))
//            ->addButtonRow(InlineKeyboardButton::make('Просмотр прикрепленных изображений 👀', callback_data: 'images@handleViewImages'))
            ->addButtonRow(InlineKeyboardButton::make('Отменить публикацию объявления ❌', callback_data: 'cancel@handleConfirmCancelEstate'))
            ->showMenu();
    }

    // Functions for cancel publication of estate

    public function handleConfirmCancelEstate(Nutgram $bot): void
    {
        $this->clearButtons()
            ->menuText("<b>Подтверждение удаления</b>\n\n Вы действительно хотите прекратить\n публикацию объявления?",
                ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('Удалить💣', callback_data: 'cancel@handleCancelEstate'))
            ->addButtonRow(InlineKeyboardButton::make('◀️Отмена', callback_data: 'preview@contact'))
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

    // Functions for payment publication of estate

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
        $this->estate->update([
            'status' => EstateStatus::pending
        ]);
        $bot->sendMessage($this->preview, '-1001875753187', parse_mode: 'html', reply_markup: InlineKeyboardMarkup::make()
            ->addRow(InlineKeyboardButton::make('Одобрить публикацию', callback_data: "approve {$this->estate->id}"))
            ->addRow(InlineKeyboardButton::make('Отклонить', callback_data: "decline {$this->estate->id}"))
        );
        $bot->forwardMessage('-1001875753187', $bot->chatId(), $bot->message()->message_id);
        $this->end();
    }

    public function none(Nutgram $bot)
    {
        $this->end();
    }
}
