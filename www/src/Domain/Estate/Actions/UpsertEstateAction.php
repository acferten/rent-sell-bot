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

class UpsertEstateAction
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
        if ($data->id != null) {
            $estate_photos = EstatePhoto::where('estate_id', $data->id);
            $estate_photos->get()
                ->each(fn($photo) => Storage::disk('photos')->delete($photo->photo));
            $estate_photos->delete();
            Storage::disk('photos')->delete(Estate::find($data->id)->main_photo);

            if (Estate::find($data->id)->video) {
                Storage::disk('photos')->delete(Estate::find($data->id)->video);
            }
            Estate::find($data->id)->prices()->delete();
        }

        $estate = Estate::updateOrCreate(
            [
                'id' => $data->id
            ],
            [
                ...$data->all(),
                'user_id' => $data->user->id,
                'main_photo' => $data->main_photo->storePublicly('', ['disk' => 'photos']),
                'video' => $data->video ? $data->video->storePublicly('', ['disk' => 'photos']) : null
            ]);

        $estate->includes()->syncWithPivotValues($data->includes->pluck('id'), ['estate_id' => $estate->id]);

        if ($data->deal_type == DealTypes::rent) {
            $estate->prices()->delete();
            $data->periods->each(fn($rent_price) => $estate->prices()->save(new EstatePrice($rent_price->all())));
        }

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
