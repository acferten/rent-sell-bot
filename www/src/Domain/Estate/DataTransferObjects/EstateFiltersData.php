<?php

namespace Domain\Estate\DataTransferObjects;

use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Models\Estate;
use Domain\Shared\DataTransferObjects\UserData;
use Illuminate\Http\Request;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Lazy;

class EstateFiltersData extends Data
{
    public function __construct(
        public readonly ?UserData  $user,
        public readonly null|array $periods,
        public readonly ?string    $deal_type,
        public readonly null|int   $price_start,
        public readonly null|int   $price_end,
        public readonly ?array     $house_type_ids,
        public readonly null|array $include_ids,
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
            'deal_type' => 'required|string|in:Аренда,Продажа',
            'include_ids' => 'array|exists:includes,id',
            'period' => 'required_if:deal_type,Аренда|string|nullable',
            'house_type_ids' => 'exists:house_types,id',
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
