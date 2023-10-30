<?php

namespace App\Http\Controllers;

use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstatePeriods;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstateInclude;
use Domain\Estate\Models\EstatePrice;
use Domain\Estate\Models\EstateType;
use Illuminate\View\View;

class EstateViewsController extends Controller
{
    public function create(): View
    {
        $data = [
            'includes' => EstateInclude::all(),
            'deal_types' => DealTypes::cases(),
            'estate_types' => EstateType::all(),
            'price_periods' => EstatePeriods::cases()
        ];

        return view('create_estate_form', $data);
    }

    public function show(Estate $estate): View
    {
        $data = [
            'estate' => $estate,
            'estate_main_photo' => $estate->main_photo,
            'estate_photos' => $estate->photos->map(fn($photo) => $photo->photo),
            'estate_video' => $estate->video,
            'estate_includes' => $estate->includes->map(fn($include) => $include->title),
            'estate_rent' => EstatePrice::where(['estate_id' => $estate->id])->first() ?? null,
        ];
        return view('view_estate', $data);
    }

    public function edit(Estate $estate): View
    {
        $data = [
            'includes' => EstateInclude::all(),
            'deal_types' => DealTypes::cases(),
            'estate_types' => EstateType::all(),
            'price_periods' => EstatePeriods::cases(),
            'estate' => $estate,
            'estate_rent' => EstatePrice::where(['estate_id' => $estate->id])->first() ?? (object)['period' => "", "price" => ""],
            'estate_house_type' => $estate->type,
            'estate_main_photo' => $estate->main_photo,
            'estate_photos' => $estate->photos->map(fn($photo) => $photo->photo),
            'estate_video' => $estate->video,
            'estate_includes' => $estate->includes->map(fn($include) => $include->title),
        ];
        return view('update_estate_form', $data);
    }
}
