<?php

namespace Domain\Estate\Traits;

use Domain\Estate\Actions\SendPreviewMessageAction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\KeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\ReplyKeyboardMarkup;

trait ChangeEstateLocation
{
    public function handleChangeLocation(Nutgram $bot): void
    {
        $bot->sendMessage(
            text: "<b>Шаг 2 из 3</b>
Отправьте геолокацию вашего объекта. Для этого перейдите во вкладку прикрепить и отправьте геолокацию боту.",
            parse_mode: 'html'
        );
        $this->closeMenu();

        $this->next('ChangeLocationStepTwo');
    }

    public function ChangeLocationStepTwo(Nutgram $bot): void
    {
        $location = $bot->message()->location;

        $this->estate->update([
            'latitude' => $location->latitude,
            'longitude' => $location->longitude
        ]);

        $this->setLocationProperties($bot);

        SendPreviewMessageAction::execute($bot, $this->estate->id);
    }

    public function setLocationProperties(Nutgram $bot): void
    {
        $locationiq_key = env('LOCATIONIQ_KEY');
        $response = Http::withHeaders([
            "Accept-Language" => "ru",
        ])->get("https://eu1.locationiq.com/v1/reverse.php?key={$locationiq_key}&lat={$this->estate->latitude}&lon={$this->estate->longitude}&format=json")->collect();
        Log::debug($response);
        if (array_key_exists('error', $response->toArray())) {
            $this->askCountry($bot);
        }
        Log::debug('response');
        Log::debug($response);
        $response = $response->get('address');
        Log::debug('address');
        Log::debug($response);

        $this->estate->update([
            'country' => $response['country'] ?? null,
            'town' => $response['city'] ?? ($response['state'] ?? null),
            'district' => $response['city_district'] ?? ($response['county'] ?? null),
            'street' => $response['road'] ?? null,
        ]);
        if (array_key_exists('house_number', $response)) {
            $this->estate->update([
                'house_number' => $response['house_number'],
            ]);
        }
    }

    public function askCountry(Nutgram $bot)
    {
        $bot->sendMessage(
            text: "Мы не смогли получить адрес из вашего местоположения. Пожалуйста, введите вручную недостающие значения.
            \nВ какой стране находится ваша недвижимость?",
            parse_mode: 'html'
        );

        $this->next('askTown');
    }

    public function askTown(Nutgram $bot)
    {
        $this->estate->update([
            'country' => $bot->message()->text,
        ]);

        $bot->sendMessage(
            text: "В каком городе или провинции находится ваша недвижимость?",
            parse_mode: 'html'
        );

        $this->next('askDistrict');
    }

    public function askDistrict(Nutgram $bot)
    {
        $this->estate->update([
            'town' => $bot->message()->text,
        ]);

        $bot->sendMessage(
            text: "В каком районе города находится ваша недвижимость?",
            parse_mode: 'html'
        );

        $this->next('askStreet');
    }

    public function askStreet(Nutgram $bot)
    {
        $this->estate->update([
            'district' => $bot->message()->text,
        ]);

        $bot->sendMessage(
            text: "На какой улице находится ваша недвижимость?",
            parse_mode: 'html'
        );

        $this->next('askHouseNumber');
    }

    public function askHouseNumber(Nutgram $bot)
    {
        $this->estate->update([
            'street' => $bot->message()->text,
        ]);

        $bot->sendMessage(
            text: "Напишите и отправьте нам номер дома вашей недвижимости",
            parse_mode: 'html'
        );

        $this->next('askContact');
    }

    public function askContact(Nutgram $bot)
    {
        $this->estate->update([
            'house_number' => $bot->message()->text,
        ]);

        $bot->sendMessage(
            text: "<b>Шаг 3 из 3</b>
Отправьте ваши контактные данные Telegram.",
            parse_mode: 'html',
            reply_markup: ReplyKeyboardMarkup::make(resize_keyboard: true, one_time_keyboard: true)->addRow(
                KeyboardButton::make('📞 Поделиться контактными данными', request_contact: true)
            ),
        );

        $this->next('contact');
    }

}
