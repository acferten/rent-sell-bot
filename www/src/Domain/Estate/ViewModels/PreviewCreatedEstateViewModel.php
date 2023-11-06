<?php

namespace Domain\Estate\ViewModels;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstateType;
use Domain\Shared\ViewModels\ToStringInterface;
use Illuminate\Support\Facades\Log;

class PreviewCreatedEstateViewModel implements ToStringInterface
{
    public static function get(Estate $estate): string
    {
        $data = EstateData::from($estate);
        $estate_type = EstateType::where(['id' => $data->house_type_id])->first()->title;
        $periods = implode(', ', $estate->prices->map(fn($price) => $price->period)->toArray());

        $preview = "Все получилось! 🥳\nВаш объект:\n\n" .
            "<b>🤝 Сделка:</b> {$data->deal_type->value}\n" .
            "<b>🎯 Включено в стоимость:</b> {$data->includes}\n" .
            "<b>🏡 Тип недвижимости:</b> {$estate_type}\n\n";

        $preview .= $data->deal_type == DealTypes::rent ? "<b>💰 Цена:</b> {$periods} - {$data->period_price}\n"
            : "<b>💰 Цена:</b> {$data->price}\n";

        return $preview;
    }
}
