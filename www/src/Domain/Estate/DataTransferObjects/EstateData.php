<?php

namespace Domain\Estate\DataTransferObjects;

use Domain\Estate\Enums\EstateStatus;

use Domain\Estate\Models\EstateType;
use Illuminate\Http\Request;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Lazy;

class EstateData extends Data
{
    public function __construct(
        public string                            $description,
        public int                               $bathrooms,
        public int                               $bedrooms,
        public int                               $conditioners,
        public int                               $price,
        public readonly null|Lazy|DataCollection $includes,
        public EstateStatus                      $status = EstateStatus::active
    )
    {
    }

    public static function fromRequest(Request $request): self
    {
        return self::from([
            ...$request->all(),
            'specialties' => EstateTypeData::collection(
                EstateType::whereIn('id', $request->collect('include_ids'))->get()
            ),
        ]);
    }

    public static function rules(): array
    {
        return [
            'header' => 'required|string|between:10,60',
            'description' => 'required|string|max:1000',
            'end_date' => 'required|int|between:1,30',
            'bedrooms' => 'required|int|between:1,10',
            'price' => 'required|int|between:0,100000',
            'address' => 'required|string',
            'include_ids' => 'array|between:0,5|exists:includes,id',
            'time_execute' => ['required', 'string', 'regex:/(^1[0-9]:00$)|(^[1-9]:00$)|(^2[0-4]:00$)|(^0[1-9]:00$)/u'],
            'status' => 'prohibited',
            'id' => 'prohibited'
        ];
    }

    public static function attributes(...$args): array
    {
        return [
            'description' => 'описание',
        ];
    }
}
