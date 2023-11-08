<?php

namespace Domain\Estate\Actions;

use Domain\Estate\DataTransferObjects\EstateFiltersData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Models\Estate;
use Illuminate\Database\Eloquent\Builder;

class GetFilteredEstatesAction
{
    public static function execute(EstateFiltersData $filters)
    {
        $estates = Estate::filter([...$filters->all()]);

        // price filter
        if ($filters->deal_type == DealTypes::rent->value) {
            if (is_null($filters->price_start)) {
                $estates->whereHas(
                    'prices', function (Builder $query) use ($filters) {
                    $query->where('price', '<=', $filters->price_end);
                });
            } else if (is_null($filters->price_end)) {
                $estates->whereHas(
                    'prices', function (Builder $query) use ($filters) {
                    $query->where('price', '>=', $filters->price_start);
                });
            } else {
                $estates->whereHas(
                    'prices', function (Builder $query) use ($filters) {
                    $query->whereBetween('price', [$filters->price_start, $filters->price_end]);
                });
            }

            if ($filters->periods) {
                $estates->whereHas('prices', function (Builder $query) use ($filters) {
                    $query->whereIn('period', $filters->periods);
                });
            }

        } else {
            if (is_null($filters->price_start)) {
                $estates->where('price', '<=', $filters->price_end);
            } else if (is_null($filters->price_end)) {
                $estates->where('price', '>=', $filters->price_start);
            } else {
                $estates->whereBetween('price', [$filters->price_start, $filters->price_end]);
            }
        }

        // estate type
        if (!is_null($filters->house_type_ids)) {
            $estates->whereHas('type', function (Builder $query) use ($filters) {
                $query->whereIn('id', $filters->house_type_ids);
            });
        }

        // estate includes
        if (!is_null($filters->include_ids)) {
            $estates->whereHas('includes', function (Builder $query) use ($filters) {
                $query->whereIn('estate_includes.id', $filters->include_ids);
            });
        }

        return $estates;
    }
}
