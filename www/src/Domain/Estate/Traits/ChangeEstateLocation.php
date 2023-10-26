<?php

namespace Domain\Estate\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;

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

        $this->getPreviewLayout();
    }

    public function setLocationProperties(Nutgram $bot): void
    {
        $locationiq_key = env('LOCATIONIQ_KEY');
        $response = Http::withHeaders([
            "Accept-Language" => "ru",
        ])->get("https://eu1.locationiq.com/v1/reverse.php?key={$locationiq_key}&lat={$this->estate->latitude}&lon={$this->estate->longitude}&format=json")->collect();
        Log::debug($response);
        if (array_key_exists('error', $response->toArray())) {
            $this->start($bot);
        }
        Log::debug('response');
        Log::debug($response);
        $response = $response->get('address');
        Log::debug('address');
        Log::debug($response);

        $this->estate->update([
            'country' => $response['country'] ?? null,
            'town' => $response['city'] ?? ($response['county'] ?? null),
            'district' => $response['city_district'] ?? ($response['village'] ?? null),
            'street' => $response['road'] ?? null,
        ]);
        if (array_key_exists('house_number', $response)) {
            $this->estate->update([
                'house_number' => $response['house_number'],
            ]);
        }
    }
}
