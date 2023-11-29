<?php

namespace App\Http\Controllers;

use Domain\Estate\DataTransferObjects\RentPeriodsData;
use Domain\Estate\Enums\BaliDistricts;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstatePeriods;
use Domain\Estate\Enums\ReportReasons;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\Amenity;
use Domain\Estate\Models\Service;
use Domain\Estate\Models\Type;
use Illuminate\View\View;

class EstateViewsController extends Controller
{
    public function create(): View
    {
        $data = [
            'services' => Service::all(),
            'amenities' => Amenity::all(),
            'deal_types' => DealTypes::cases(),
            'estate_types' => Type::all(),
            'price_periods' => EstatePeriods::cases(),
            'custom_districts' => BaliDistricts::cases()
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
            'check_in_date' => date('d.m.Y', strtotime($estate->available_date)),
            'estate_amenities' => $estate->amenities->map(fn($amenity) => $amenity->title),
            'estate_services' => $estate->services->map(fn($service) => $service->title),
            'estate_rent' => $estate->prices->map(fn($rent_price) => RentPeriodsData::from($rent_price)) ?? null,
            'report_reasons' => ReportReasons::cases()
        ];
        return view('view_estate', $data);
    }

    public function edit(Estate $estate): View
    {
        $data = [
            'deal_types' => DealTypes::cases(),
            'estate_types' => Type::all(),
            'price_periods' => EstatePeriods::cases(),
            'estate' => $estate,
            'estate_rent' => $estate->prices->map(fn($rent_price) => RentPeriodsData::from($rent_price)) ?? null,
            'estate_house_type' => $estate->type,
            'estate_main_photo' => $estate->main_photo,
            'estate_photos' => $estate->photos->map(fn($photo) => $photo->photo),
            'estate_video' => $estate->video,
            'check_in_date' => date('Y-m-d', strtotime($estate->available_date)),
            'services' => Service::all(),
            'amenities' => Amenity::all(),
            'custom_districts' => BaliDistricts::cases(),
            'estate_custom_district' => $estate->custom_district,
            'estate_amenities' => $estate->amenities->map(fn($amenity) => $amenity->title),
            'estate_services' => $estate->services->map(fn($service) => $service->title),
        ];
        return view('update_estate_form', $data);
    }
}
