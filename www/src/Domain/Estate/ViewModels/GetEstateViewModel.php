<?php

namespace Domain\Estate\ViewModels;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstateType;
use Domain\Shared\ViewModels\ToStringInterface;
use Illuminate\Support\Facades\Log;

class GetEstateViewModel implements ToStringInterface
{
    public static function get(Estate $estate): string
    {
        $data = EstateData::from($estate);
        $estate_type = EstateType::where(['id' => $data->house_type_id])->first()->title;
        $price = '';

        if ($data->deal_type == DealTypes::rent) {
            foreach ($data->periods as $rent_periods) {
                $price .= "<b>💰 Цена за {$rent_periods->period->value}:</b> {$rent_periods->price}\n";
            }
        } else {
            $price = "<b>💰 Цена:</b> {$data->price}";
        }

        return "🤝 {$data->deal_type->value}\n" .
            "🏡 {$estate_type}\n" .
            "🛏 {$data->bedrooms} спальни\n\n" .
            "<b>📍Локация:</b > {$data->district}\n" .
            "{$price}\n";
    }
}
