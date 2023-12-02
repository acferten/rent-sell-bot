<?php

namespace Domain\Estate\Actions;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\Photo;
use Domain\Estate\Models\Price;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;
use SergiX44\Nutgram\Telegram\Web\WebAppUser;

class UpdateEstateAction
{
    public static function execute(EstateData $data, WebAppUser $user = null): Estate
    {
        $estate = Estate::findOrFail($data->id);

        if (!is_null($user)) {
            throw_if($estate->user_id != $user->id, new UnauthorizedException);
        }

        $estate_photos = $estate->photos();
        $estate_photos->get()
            ->each(fn($photo) => Storage::disk('photos')->delete($photo->photo));
        $estate_photos->delete();
        Storage::disk('photos')->delete($estate->main_photo);

        $estate->video ?
            Storage::disk('photos')->delete($estate->video) : null;

        $estate->prices()->delete();

        $estate->update([
            ...$data->all(),
            'status' => $estate->status,
            'main_photo' => $data->main_photo->storePublicly('', ['disk' => 'photos']),
            'video' => $data->video ? $data->video->storePublicly('', ['disk' => 'photos']) : null]);

        $estate->amenities()->syncWithPivotValues($data->amenities->pluck('id'), ['estate_id' => $estate->id]);
        $estate->services()->syncWithPivotValues($data->services->pluck('id'), ['estate_id' => $estate->id]);

        if ($data->deal_type == DealTypes::rent) {
            $data->periods->each(fn($rent_price) => $estate->prices()->save(new Price($rent_price->all())));
        }

        if ($data->photo) {
            foreach ($data->photo as $photo) {
                $estate->photos()->save(new Photo([
                    'photo' => $photo->storePublicly('', ['disk' => 'photos'])
                ]));
            }
        }
        return $estate;
    }
}
