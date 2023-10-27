<?php

namespace App\Http\Controllers;

use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstatePeriods;
use Domain\Estate\Models\EstateInclude;
use Domain\Estate\Models\EstateType;
use Domain\Estate\Models\Estate;
use Illuminate\Http\Request;
use Illuminate\View\View;


class EstateFiltersFormController extends Controller
{
    public function __invoke(Request $request): View
    {
        $data = [
            'includes' => EstateInclude::all(),
            'deal_types' => DealTypes::cases(),
            'estate_types' => EstateType::all(),
            'price_periods' => EstatePeriods::cases(),
            'countries' => Estate::all()->map(fn($estate) => $estate->country),
        ];
        return view('estate_filters', $data);
    }
}
