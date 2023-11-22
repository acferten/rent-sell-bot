<?php

namespace Domain\Estate\ViewModels;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\Type;
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
            "✅ Poster: #{$estate->id}\n" .
            "Created: {$estate->created_at}\n" .
            "Updated: {$estate->updated_at}\n" .
            "User id: {$estate->user->id}\n" .
            "User login TG: @{$estate->user->username}\n" .
            "User poster count: {$estate->user->estates->count()}\n" .
            "Number TG: {$estate->user->phone}\n" .
            "💰Order price: 300.000 IDR\n\n" .

            "<b>Status: {$estate->status}</b>" .

            "<b>Статус: {$estate->status}\n\n</b>" .
            "<b>🏡 Тип недвижимости:</b> {$estate->type->title}\n" .
            "🛏 {$data->bedrooms} спален\n" .
            "{$estate->getGoogleLink()}" .
            $price;
    }
}
