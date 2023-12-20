<?php

namespace Domain\Estate\Traits;

use Domain\Estate\Actions\SendPreviewMessageAction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;

trait SetLocationProperties
{
    public function setLocationProperties(Nutgram $bot): void
    {
        $locationiq_key = env('LOCATIONIQ_KEY');
        $response = Http::withHeaders([
            "Accept-Language" => "ru",
        ])->get("https://eu1.locationiq.com/v1/reverse.php?key={$locationiq_key}&lat={$this->estate->latitude}&lon={$this->estate->longitude}&format=json")->collect();

        if (array_key_exists('error', $response->toArray())) {
            $bot->sendMessage(
                text: "<b>Не удалось получить адрес из отправленной геолокации. Попробуйте еще раз.</b>
📍 Отправьте геолокацию вашего объекта.
👉 Вставьте ссылку из Google Maps или отправьте текущую Геопозицию.",
                parse_mode: 'html'
            );

            $this->next('change');
        }
        $response = $response->get('address');

        $this->estate->update([
            'country' => $response['country'] ?? null,
            'state' => $response['state'] ?? null,
            'county' => $response['county'] ?? null,
            'town' => $response['city'] ?? ($response['town'] ?? ($response['village'] ?? null)),
            'district' => $response['city_district'] ?? null,
            'street' => $response['road'] ?? null,
        ]);

        if (array_key_exists('house_number', $response)) {
            $this->estate->update([
                'house_number' => $response['house_number'],
            ]);
        }
    }
}
