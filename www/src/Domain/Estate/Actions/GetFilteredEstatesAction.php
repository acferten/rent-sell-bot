<?php

namespace Domain\Estate\Actions;

use Domain\Estate\DataTransferObjects\EstateFiltersData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use Illuminate\Database\Eloquent\Builder;

class GetFilteredEstatesAction
{
    public static function execute(EstateFiltersData $filters): Builder
    {
        $estates = Estate::filter([...$filters->all()]);

        // price filter
        if ($filters->deal_type == DealTypes::rent->value) {
            if (!is_null($filters->price_start)) {
                $estates->whereHas(
                    'prices', function (Builder $query) use ($filters) {
                    $query->where('price', '>=', $filters->price_start);
                });
            }
            if (!is_null($filters->price_end)) {
                $estates->whereHas(
                    'prices', function (Builder $query) use ($filters) {
                    $query->where('price', '<=', $filters->price_end);
                });
            }
            // periods filter
            if ($filters->periods) {
                $estates->whereHas('prices', function (Builder $query) use ($filters) {
                    $query->whereIn('period', $filters->periods);
                });
            }
        } else {
            if (!is_null($filters->price_start)) {
                $estates->where('price', '>=', $filters->price_start);
            }
            if (!is_null($filters->price_end)) {
                $estates->where('price', '<=', $filters->price_end);
            }
        }

        // estate type
        if (!is_null($filters->house_type_ids)) {
            $estates->whereHas('type', function (Builder $query) use ($filters) {
                $query->whereIn('id', $filters->house_type_ids);
            });
        }

        // estate amenities
        if (!is_null($filters->amenity_ids)) {
            $estates->whereHas('amenities', function (Builder $query) use ($filters) {
                $query->whereIn('amenity_estate.amenity_id', $filters->amenity_ids);
            });
        }

        // estate services
        if (!is_null($filters->service_ids)) {
            $estates->whereHas('services', function (Builder $query) use ($filters) {
                $query->whereIn('estate_service.service_id', $filters->service_ids);
            });
        }

        $estates->where('status', EstateStatus::active->value);
        $estates->orderBy('relevance_date', 'DESC');

        return $estates;
    }
}
