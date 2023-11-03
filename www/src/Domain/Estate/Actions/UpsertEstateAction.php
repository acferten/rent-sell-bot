<?php

namespace Domain\Estate\Actions;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstatePhoto;
use Domain\Estate\Models\EstatePrice;
use Domain\Shared\Models\Actor\User;
use Illuminate\Support\Facades\Log;

class UpsertEstateAction
{
    public static function execute(EstateData $data): Estate
    {
        $user = User::updateOrCreate(
            [
                'id' => $data->user->id
            ],
            [
                ...$data->user->all(),
            ]
        );

        $estate = Estate::updateOrCreate(
            [
                'id' => $data->id
            ],
            [
                ...$data->all(),
                'user_id' => $user->id,
                'main_photo' => $data->main_photo->storePublicly('', ['disk' => 'photos']),

            ]);

        $data->video ?
            $estate->update(['video' => $data->video->storePublicly('', ['disk' => 'photos']),]) : null;

        $estate->includes()->syncWithPivotValues($data->includes->pluck('id'), ['estate_id' => $estate->id]);

        if ($data->deal_type == DealTypes::rent) {
            EstatePrice::create([
                'period' => $data->period,
                'price' => $data->period_price,
                'estate_id' => $estate->id
            ]);
        }

        $data->id != null ?
            EstatePhoto::where('estate_id', $data->id)->delete() : null;

        if ($data->photo) {
            foreach ($data->photo as $photo) {
                EstatePhoto::create([
                    'photo' => $photo->storePublicly('', ['disk' => 'photos']),
                    'estate_id' => $estate->id
                ]);
            }
        }
        return $estate;
    }
}
