<?php

namespace Domain\Estate\Actions;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstatePhoto;
use Domain\Estate\Models\EstatePrice;
use Domain\Shared\Models\Actor\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CreateEstateAction
{
    public static function execute(EstateData $data): Estate
    {
        User::updateOrCreate(
            [
                'id' => $data->user->id
            ],
            [
                ...$data->user->all(),
            ]
        );

        $estate = Estate::create(
            [
                ...$data->all(),
                'user_id' => $data->user->id,
                'main_photo' => $data->main_photo->storePublicly('', ['disk' => 'photos']),
                'video' => $data->video ? $data->video->storePublicly('', ['disk' => 'photos']) : null
            ]);

        $estate->includes()->syncWithPivotValues($data->includes->pluck('id'), ['estate_id' => $estate->id]);

        if ($data->deal_type == DealTypes::rent) {
            $data->periods->each(fn($rent_price) => $estate->prices()->save(new EstatePrice($rent_price->all())));
        }

        if ($data->photo) {
            foreach ($data->photo as $photo) {
                $estate->photos()->save(new EstatePhoto([
                    'photo' => $photo->storePublicly('', ['disk' => 'photos'])
                ]));
            }
        }
        return $estate;
    }
}
