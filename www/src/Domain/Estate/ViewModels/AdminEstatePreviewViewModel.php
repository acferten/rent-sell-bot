<?php

namespace Domain\Estate\ViewModels;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstateType;
use Domain\Shared\ViewModels\ToStringInterface;

class AdminEstatePreviewViewModel implements ToStringInterface
{
    public static function get(Estate $estate): string
    {
        $data = EstateData::from($estate);
        $estate_type = EstateType::where(['id' => $data->house_type_id])->first()->title;
        $periods = implode(', ', $estate->prices->map(fn($price) => $price->period)->toArray());

        $preview = "<b>Статус: {$estate->status}\n\n</b>" .
            "<b>Сделка:</b> {$data->deal_type->value}\n" .
            "<b>Тип недвижимости:</b>  {$estate_type}\n" .
            "<b>Описание:</b> {$data->description}\n\n" .
            "<b>Количество спален:</b> {$data->bedrooms}\n" .
            "<b>Количество ванных комнат:</b> {$data->bathrooms}\n" .
            "<b>Количество кондиционеров:</b> {$data->conditioners}\n" .
            "<b>Включено в стоимость:</b> {$data->includes}\n";

        $preview .= $data->deal_type == DealTypes::rent ? "<b>Период аренды:</b> {$periods}\n<b>Цена за весь период:</b> {$data->period_price}\n"
            : "<b>Цена:</b> {$data->price}\n";

        return $preview;
    }
}
