<?php

namespace App\Http\Controllers;

use Domain\Estate\DataTransferObjects\RentPeriodsData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstatePeriods;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\Amenity;
use Domain\Estate\Models\Price;
use Domain\Estate\Models\Type;
use Illuminate\View\View;

class EstateViewsController extends Controller
{
    public function create(): View
    {
        $data = [
            'includes' => Amenity::all(),
            'deal_types' => DealTypes::cases(),
            'estate_types' => Type::all(),
            'price_periods' => EstatePeriods::cases()
        ];

        return view('create_estate_form', $data);
    }

    public function show(Estate $estate): View
    {
        $data = [
            'estate' => $estate,
            'estate_main_photo' => $estate->main_photo,
            'estate_photos' => [$estate->main_photo, ...$estate->photos->map(fn($photo) => $photo->photo)],
            'estate_video' => $estate->video,
            'estate_includes' => $estate->includes->map(fn($include) => $include->title),
            'estate_rent' => $estate->prices->map(fn($rent_price) => RentPeriodsData::from($rent_price)) ?? null,
        ];
        return view('view_estate', $data);
    }

    public function edit(Estate $estate): View
    {
        $data = [
            'includes' => Amenity::all(),
            'deal_types' => DealTypes::cases(),
            'estate_types' => Type::all(),
            'price_periods' => EstatePeriods::cases(),
            'estate' => $estate,
            'estate_rent' => $estate->prices->map(fn($rent_price) => RentPeriodsData::from($rent_price)) ?? null,
            'estate_house_type' => $estate->type,
            'estate_main_photo' => $estate->main_photo,
            'estate_photos' => $estate->photos->map(fn($photo) => $photo->photo),
            'estate_video' => $estate->video,
            'estate_includes' => $estate->includes->map(fn($include) => $include->title),
        ];
        return view('update_estate_form', $data);
    }
}
