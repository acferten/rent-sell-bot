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
        public readonly null|Collection|string             $includes,
        public readonly null|array|UploadedFile|Collection $photo,
        public readonly ?UserData                          $user,
        public readonly null|UploadedFile                  $video,
        public readonly UploadedFile                       $main_photo,
        public readonly null|string|EstatePeriods|Lazy     $period,
        public readonly null|int|string                    $period_price,
        public readonly int                                $house_type_id,
        public readonly null|int                           $chat_id,
        public readonly DealTypes                          $deal_type,
        public readonly string|null                        $country,
        public readonly string|null                        $town,
        public readonly string|null                        $street,
        public readonly string|null                        $district,
        public readonly int|null|string                    $house_number,
        public EstateStatus                                $status = EstateStatus::notFinished
    )
    {
    }

    public static function fromModel(Estate $estate): self
    {
        return self::from([
            ...$estate->toArray(),
            'photo' => EstatePhoto::where(['estate_id' => $estate->id])->get()->pluck('photo'),
            'includes' => implode(', ', $estate->includes->map(fn($include) => $include->title)->toArray()),
            'user' => UserData::from($estate->user),
            'period_price' => implode(', ', $estate->prices->map(fn($price) => $price->price)->toArray()),
        ]);
    }

    public static function fromRequest(Request $request): self
    {
        return self::from([
            ...$request->all(),
            'id' => $request->estate?->id,
            'includes' => EstateInclude::whereIn('id', $request->collect('include_ids'))->get(),
            'photo' => $request->file('photo') ?? $request->file('photo'),
            'user' => $request->user_id != null ? UserData::from([
                'id' => $request->input('user_id'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'username' => $request->input('username'),
            ]) : null,
            'price' => $request->input('deal_type') == DealTypes::sale->value ? $request->input('price') : null
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
            'main_photo' => 'required',
            'price' => 'required_if:deal_type,Продажа|int|between:0,100000|nullable',
            'include_ids' => 'array|exists:includes,id',
            'video' => 'mimetypes:video/avi,video/mpeg,video/quicktime|max:11200',
            'period' => 'required_if:deal_type,Аренда|string|nullable',
            'period_price' => 'required_if:deal_type,Аренда|int|nullable',
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
