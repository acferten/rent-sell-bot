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

        if ($data->deal_type == DealTypes::rent) {
            $price = "<b>💰 Цена</b>\n";
            foreach ($data->periods as $rent_periods) {
                $price .= "За {$rent_periods->period->value} - {$rent_periods->price} млн. IDR\n";
            }
        } else {
            $price = "<b>💰 Цена:</b> {$data->price}";
        }
        $test = trans_choice('bedrooms', $data->bedrooms);
        return "<b>{$data->title}</b>\n\n" .
            $price .
            "\n🏡 {$estate->type->title}\n" .
            "🛏 {$data->bedrooms}{$test}\n\n" .
            "<b>📍Локация:</b > {$estate->custom_district}\n" .
            "<b>📍Google maps:</b > {$estate->getGoogleLink()}\n\n";
    }
}
