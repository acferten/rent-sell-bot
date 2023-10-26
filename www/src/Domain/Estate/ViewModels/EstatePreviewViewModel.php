<?php

namespace Domain\Estate\ViewModels;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstateType;
use Domain\Shared\ViewModels\ToStringInterface;

class EstatePreviewViewModel implements ToStringInterface
{
    public static function get(Estate $estate): string
    {
        $data = EstateData::from($estate);
        $estate_type = EstateType::where(['id' => $data->house_type_id])->first()->title;
        $periods = implode(', ', $estate->prices->map(fn($price) => $price->period)->toArray());

        $preview = "<b>Статус:  {$estate->status}\n\n</b>" .
            "<b>Сделка:</b> {$data->deal_type->value}\n" .
            "<b>Тип недвижимости:</b>  {$estate_type}\n" .
            "<b>Описание:</b> {$data->description}\n\n" .
            "<b>Количество просмотров:  {$estate->views}\n</b>" .
            "<b>Количество переходов в сообщения:  {$estate->chattings}\n</b>";

        $preview .= $data->deal_type == DealTypes::rent ? "<b>Период аренды:</b> {$periods}\n<b>Цена за весь период:</b> {$data->period_price}\n"
            : "<b>Цена:</b> {$data->price}\n";

        return $preview;
    }
}
