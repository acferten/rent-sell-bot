<?php

namespace Domain\Estate\Actions;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstatePrice;
use Domain\Shared\Models\Actor\User;

class CreateEstateAction
{
    public static function execute(EstateData $data): void
    {
//        $user = User::updateOrCreate(
//            [
//                'id' => $data->user->id
//            ],
//            [
//                ...$data->user->all()
//            ]
//        );

        $estate = Estate::create([
            ...$data->all(),
            'user_id' => $user->id,
        ]);

        $estate->includes()->syncWithPivotValues($data->includes->toCollection()->pluck('id'), ['estate_id' => $estate->id]);

            $data->period ?? EstatePrice::create([
            'period' => $data->period,
            'price' => $data->period_price,
            'estate_id' => $estate->id
        ]); //TODO: ОТДЕЛЬНЫЙ DTO И ВЛОЖИТЬ ЕГО В ESTATE DATA
    }
}
