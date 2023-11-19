<?php

namespace Domain\Estate\ViewModels;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\Type;
use Domain\Shared\ViewModels\ToStringInterface;
use Illuminate\Support\Facades\Log;

class GetEstateViewModel implements ToStringInterface
{
    public static function get(Estate $estate): string
    {
        $data = EstateData::from($estate);
        $price = '';

        if ($data->deal_type == DealTypes::rent) {
            foreach ($data->periods as $rent_periods) {
                $price .= "<b>💰 Цена за {$rent_periods->period->value}:</b> {$rent_periods->price}\n";
            }
        } else {
            $price = "<b>💰 Цена:</b> {$data->price}";
        }

        return "🤝 {$data->deal_type->value}\n" .
            "🏡 {$estate->type->title}\n" .
            "🛏 {$data->bedrooms} спальни\n\n" .
            "<b>📍Локация:</b > {$estate->geoposition()}\n" .
            "<b>📍Google maps:</b > {$estate->getGoogleLink()}\n\n" .
            "{$price}\n";
    }
}
