<?php

namespace Domain\Estate\DataTransferObjects;

use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstatePeriods;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\EstateInclude;
use Domain\Shared\DataTransferObjects\UserData;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class EstateData extends Data
{
    public function __construct(
        public string                           $description,
        public int                              $bedrooms,
        public int                              $conditioners,
        public int                              $bathrooms,
        public null|int                         $price,
        /** @var DataCollection<EstateIncludeData> */
        public readonly null|DataCollection     $includes,
        /** @var DataCollection<EstatePriceData> */
        public readonly null|DataCollection     $per,
        public readonly null|array|UploadedFile $photo,
        public readonly UserData                $user,
        public readonly null|UploadedFile       $video_review,
        public readonly null|EstatePeriods      $period,
        public readonly null|int                $period_price,
        public readonly int                     $house_type_id,
        public readonly DealTypes               $deal_type,
        public EstateStatus                     $status = EstateStatus::pending
    )
    {
    }

    public static function fromRequest(Request $request): static
    {
        return self::from([
            ...$request->all(),
            'includes' => EstateIncludeData::collection(
                EstateInclude::whereIn('id', $request->collect('include_ids'))->get()
            ),
            'user' => UserData::from([
                'id' => $request->input('user_id'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'username' => $request->input('username'),
            ]),
        ]);
    }

    public static function rules(): array
    {
        return [
            'description' => 'required|string|max:1000',
            'deal_type' => 'required',
            'bedrooms' => 'required|int|between:1,10',
            'bathrooms' => 'required|int|between:1,10',
            'conditioners' => 'required|int|between:0,25',
            'price' => 'required_if:deal_type,Продажа|int|between:0,100000',
            'include_ids' => 'array|exists:includes,id',
            'photo' => 'required|image|max:5120|mimes:jpg,png',
            'video_review' => 'mimetypes:video/avi,video/mpeg,video/quicktime|max:11200',
            'period' => 'required_if:deal_type,Аренда|string',
            'period_price' => 'required_if:deal_type,Аренда|int',
            'house_type_id' => 'required|exists:house_types,id',
            'user_id' => 'required',
            'username' => 'required',
            'first_name' => 'required'
        ];
    }

    public static function attributes(...$args): array
    {
        return [
            'description' => 'описание',
        ];
    }
}
