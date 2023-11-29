<?php

namespace Domain\Estate\DataTransferObjects;

use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\Amenity;
use Domain\Estate\Models\Photo;
use Domain\Estate\Models\Service;
use Domain\Shared\DataTransferObjects\UserData;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\WithoutValidation;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class EstateData extends Data
{
    public function __construct(
        public readonly ?int                               $id,
        public string                                      $description,
        public string                                      $title,
        public string                                      $available_date,
        public string                                      $custom_district,
        public int                                         $bedrooms,
        public int                                         $conditioners,
        public int                                         $bathrooms,
        public null|int                                    $price,
        #[WithoutValidation]
        /** @var DataCollection<RentPeriodsData> */
        public null|DataCollection                         $periods,
        public readonly null|Collection|string             $amenities,
        public readonly null|Collection|string             $services,
        public readonly null|array|UploadedFile|Collection $photo,
        public readonly ?UserData                          $user,
        public readonly null|UploadedFile|string           $video,
        public readonly UploadedFile|string                $main_photo,
        #[WithoutValidation]
        public readonly int                                $type_id,
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
            'photo' => Photo::where(['estate_id' => $estate->id])->get()->pluck('photo'),
            'user' => UserData::from($estate->user),
            'periods' => RentPeriodsData::collection($estate->prices),
            'amenities' => implode(', ', $estate->amenities->map(fn($amenity) => $amenity->title)->toArray()),
            'services' => implode(', ', $estate->services->map(fn($service) => $service->title)->toArray())
        ]);
    }

    public static function fromRequest(Request $request): self
    {
        return self::from([
            ...$request->all(),
            'id' => (int)$request->estate,
            'amenities' => Amenity::whereIn('id', $request->collect('amenity_ids'))->get(),
            'services' => Service::whereIn('id', $request->collect('service_ids'))->get(),
            'photo' => $request->file('photo'),
            'user' => UserData::from([
                'id' => $request->input('user_id'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'username' => $request->input('username'),
            ]),
            'type_id' => $request->input('house_type_id'),
            'custom_district' => $request->input('custom_district'),
            'periods' => RentPeriodsData::collection(collect(json_decode($request->input('periods')))),
            'price' => $request->input('deal_type') == DealTypes::sale->value ? $request->input('price') : null
        ]);
    }

    public static function rules(): array
    {
        return [
            'title' => 'required|string|max:80',
            'description' => 'required|string|max:400',
            'available_date' => 'required|date',
            'custom_district' => 'required|string',
            'deal_type' => 'required|in:Аренда,Продажа',
            'price' => 'required_if:deal_type,Продажа|int|between:0,10000000|nullable',

            'bedrooms' => 'required|int|between:1,10',
            'bathrooms' => 'required|int|between:1,10',
            'conditioners' => 'required|int|between:0,25',

            'main_photo' => 'required|image|max:4192',
            'photo' => 'required|array',
            'photo*' => 'image|max:4192',
            'video' => 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4|max:30000',

            'amenity_ids' => 'array|exists:amenities,id',
            'service_ids' => 'array|exists:services,id',
            'house_type_id' => 'required|exists:types,id',

            'user_id' => 'required|min:1',
            'username' => 'required|string',
            'first_name' => 'required|string'
        ];
    }

    public static function attributes(...$args): array
    {
        return [
            'description' => 'описание',
            'deal_type' => 'тип услуги',
            'house_type_id' => 'тип недвижимости',
            'bedrooms' => 'количество спален',
            'bathrooms' => 'количество ванных комнат',
            'conditioners' => 'количество кондиционеров',
            'main_photo' => 'главное фото'
        ];
    }
}
