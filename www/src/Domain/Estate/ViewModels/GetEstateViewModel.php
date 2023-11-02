<?php

namespace Domain\Estate\ViewModels;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstateType;
use Domain\Shared\ViewModels\ToStringInterface;

class GetEstateViewModel implements ToStringInterface
{
    public static function get(Estate $estate): string
    {
        $data = EstateData::from($estate);
        $estate_type = EstateType::where(['id' => $data->house_type_id])->first()->title;
        $periods = implode(', ', $estate->prices->map(fn($price) => $price->period)->toArray());

        $preview = "🤝 {$data->deal_type->value}\n" .
            "🏡 {$estate_type}\n" .
            "🛏 {$data->bedrooms} спальни\n\n" .
            "<b>📍 Локация:</b> {$data->district}\n";

        $preview .= $data->deal_type == DealTypes::rent ? "<b>💰 Цена:</b> {$periods} - {$data->period_price}\n"
            : "<b>💰 Цена:</b> {$data->price}\n";

        return $preview;
    }
}
