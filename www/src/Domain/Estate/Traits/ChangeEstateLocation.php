<?php

namespace Domain\Estate\Traits;

use Domain\Estate\Actions\SendPreviewMessageAction;
use Illuminate\Support\Facades\Http;
use SergiX44\Nutgram\Nutgram;

trait ChangeEstateLocation
{
    public function setLocationProperties(Nutgram $bot): void
    {
        $locationiq_key = env('LOCATIONIQ_KEY');
        $response = Http::withHeaders([
            "Accept-Language" => "ru",
        ])->get("https://eu1.locationiq.com/v1/reverse.php?key={$locationiq_key}&lat={$this->estate->latitude}&lon={$this->estate->longitude}&format=json")->collect();

        if (array_key_exists('error', $response->toArray())) {
            $this->askCountry($bot);
        }

        $response = $response->get('address');

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

    public function askTown(Nutgram $bot): void
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

    public function askDistrict(Nutgram $bot): void
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

    public function askStreet(Nutgram $bot): void
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

    public function askHouseNumber(Nutgram $bot): void
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

    public function askContact(Nutgram $bot): void
    {
        $this->estate->update([
            'house_number' => $bot->message()->text,
        ]);

        SendPreviewMessageAction::execute($bot, $this->estate->id);
    }

}
