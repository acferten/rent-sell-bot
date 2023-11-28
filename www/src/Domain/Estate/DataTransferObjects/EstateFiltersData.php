<?php

namespace Domain\Estate\DataTransferObjects;

use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Models\Amenity;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\Service;
use Domain\Shared\DataTransferObjects\UserData;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

class EstateFiltersData extends Data
{
    public function __construct(
        public readonly ?UserData                    $user,
        public readonly null|array                   $periods,
        public readonly ?string                      $deal_type,
        public readonly ?string                      $country,
        public readonly ?string                      $state,
        public readonly ?string                      $county,
        public readonly ?string                      $town,
        public readonly ?int                         $price_start,
        public readonly ?int                         $price_end,
        public readonly ?array                       $house_type_ids,
        public ?string                               $available_date,
        public ?string                               $custom_district,
        public readonly null|Collection|string|array $amenity_ids,
        public readonly null|Collection|string|array $service_ids,
    )
    {
    }

    public static function fromModel(Estate $estate): self
    {
        return self::from([
            ...$estate->toArray(),
        ]);
    }

    public static function fromRequest(Request $request): self
    {
        return self::from([
            ...$request->all(),
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
            'amenity_ids' => 'array|exists:amenities,id',
            'service_ids' => 'array|exists:services,id',
            'deal_type' => 'required|string|in:Аренда,Продажа',
            'house_type_ids' => 'exists:types,id',
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
