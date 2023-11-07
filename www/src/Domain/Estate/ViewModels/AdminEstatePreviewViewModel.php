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

        $price = '';
        if ($data->deal_type == DealTypes::rent) {
            foreach ($data->periods as $rent_periods) {
                $price .= "<b>💰 Цена за {$rent_periods->period->value}:</b> {$rent_periods->price}\n";
            }
        } else {
            $price = "<b>💰 Цена:</b> {$data->price}";
        }

        return "<b>Статус: {$estate->status}\n\n</b>" .
            "<b>🤝 Сделка:</b> {$data->deal_type->value}\n" .
            "<b>🎯 Включено в стоимость:</b> {$data->includes}\n" .
            "<b>🏡 Тип недвижимости:</b> {$estate->type->title}\n" .
            "{$data->bedrooms} спален\n" .
            "{$data->bathrooms} ванных комнат\n" .
            "{$data->conditioners} кондиционеров\n" .
            "<b>Описание:</b> {$data->description}\n\n" .
            $price;
    }
}
