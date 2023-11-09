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

        return
            "Poster: #{$estate->id}\n" .
            "Created: {$estate->created_at}\n" .
            "User id: {$estate->user->id}\n" .
            "User login TG: @{$estate->user->username}\n" .
            "User poster count: 4\n" .
            "Number TG: {$estate->user->phone}\n" .
            "📍Object location: {$estate->getGoogleLink()}\n" .
            "👛Rate: 5 / 30 days\n" .
            "💰Order price: 150.000 IDR\n" .
            "Payment: transfer to BRI Bank card\n" .
            "Paid: paid by BRI Bank card .\n" .
            "Rating: 4🔑\n" .

            "<b>Статус: {$estate->status}\n\n</b>" .
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
