<?php

namespace Domain\Estate\DataTransferObjects;

use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstatePeriods;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstateInclude;
use Domain\Estate\Models\EstatePhoto;
use Domain\Shared\DataTransferObjects\UserData;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;

class EstateData extends Data
{
    public function __construct(
        public readonly ?int                               $id,
        public string                                      $description,
        public int                                         $bedrooms,
        public int                                         $conditioners,
        public int                                         $bathrooms,
        public null|int                                    $price,
        /** @var DataCollection<RentPeriodsData> */
        public null|DataCollection                         $periods,
        public readonly null|Collection|string             $includes,
        public readonly null|array|UploadedFile|Collection $photo,
        public readonly ?UserData                          $user,
        public readonly null|UploadedFile|string           $video,
        public readonly UploadedFile|string                $main_photo,
        public readonly int                                $house_type_id,
        public readonly null|int                           $chat_id,
        public readonly DealTypes                          $deal_type,
        public EstateStatus                                $status = EstateStatus::notFinished
    )
    {
    }

    public static function fromModel(Estate $estate): self
    {
        return self::from([
            ...$estate->toArray(),
            'photo' => EstatePhoto::where(['estate_id' => $estate->id])->get()->pluck('photo'),
            'user' => UserData::from($estate->user),
            'periods' => RentPeriodsData::collection($estate->prices),
            'includes' => implode(', ', $estate->includes->map(fn($include) => $include->title)->toArray())
        ]);
    }

    public static function fromRequest(Request $request): self
    {
        return self::from([
            ...$request->all(),
            'id' => (int)$request->estate,
            'includes' => EstateInclude::whereIn('id', $request->collect('include_ids'))->get(),
            'photo' => $request->file('photo'),
            'user' => UserData::from([
                'id' => $request->input('user_id'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'username' => $request->input('username'),
            ]),
            'periods' => RentPeriodsData::collection(collect(json_decode($request->input('periods')))),
            'price' => $request->input('deal_type') == DealTypes::sale->value ? $request->input('price') : null
        ]);
    }

    public static function rules(): array
    {
        return [
            'description' => 'required|string|max:1000',

            'deal_type' => 'required|in:Аренда,Продажа',
            'price' => 'required_if:deal_type,Продажа|int|between:0,10000000|nullable',

            'bedrooms' => 'required|int|between:1,10',
            'bathrooms' => 'required|int|between:1,10',
            'conditioners' => 'required|int|between:0,25',

            'main_photo' => 'required|image',
            'photo' => 'required|array',
            'photo*' => 'image',
            'video' => 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4',

            'include_ids' => 'array|exists:includes,id',
            'house_type_id' => 'required|exists:house_types,id',

            'user_id' => 'required|min:1',
            'username' => 'required|string',
            'first_name' => 'required|string'
        ];
    }

    public static function attributes(...$args): array
    {
        return [
            'description' => 'описание',
        ];
    }
}
