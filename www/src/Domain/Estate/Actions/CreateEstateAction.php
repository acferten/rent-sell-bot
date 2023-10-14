<?php

namespace Domain\Estate\Actions;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstatePrice;
use Domain\Shared\Models\Actor\User;
use Illuminate\Support\Facades\Log;

class CreateEstateAction
{
    public static function execute(EstateData $data): Estate
    {
        $user = User::updateOrCreate(
            [
                'id' => $data->user->id
            ],
            [
                ...$data->user->all()
            ]
        );

        $estate = Estate::create([
            ...$data->all(),
            'user_id' => $user->id,
        ]);

        $estate->includes()->syncWithPivotValues($data->includes->toCollection()->pluck('id'), ['estate_id' => $estate->id]);

            $data->deal_type == DealTypes::rent->value ?? EstatePrice::create([
            'period' => $data->period,
            'price' => $data->period_price,
            'estate_id' => $estate->id
        ]);

        Log::debug($data->photo);


//
//        foreach ()
//        $user->update([
//            'avatar_path' => $data->avatar->storePublicly('', ['disk' => 'avatars']),
//        ]);


        return $estate;
    }
}
