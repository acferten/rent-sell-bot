<?php

namespace Domain\Estate\Actions;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\Photo;
use Domain\Estate\Models\Price;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\UnauthorizedException;
use SergiX44\Nutgram\Telegram\Web\WebAppUser;

class UpdateEstateAction
{
    public static function execute(EstateData $data, WebAppUser $user): Estate
    {
        $estate = Estate::findOrFail($data->id);

        if ($estate->user_id != $user->id) {
            throw new UnauthorizedException;
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
            'user_id' => $data->user->id,
            'main_photo' => $data->main_photo->storePublicly('', ['disk' => 'photos']),
            'video' => $data->video ? $data->video->storePublicly('', ['disk' => 'photos']) : null]);

        $estate->includes()->syncWithPivotValues($data->includes->pluck('id'), ['estate_id' => $estate->id]);

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
