<?php

namespace App\Http\Controllers;

use Domain\Estate\Actions\SaveUserFiltersAction;
use Domain\Estate\DataTransferObjects\EstateFiltersData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstatePeriods;
use Domain\Estate\Models\EstateInclude;
use Domain\Estate\Models\EstateType;
use Domain\Estate\Models\Estate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class EstateFiltersController extends Controller
{
    public function get(): View
    {
        $data = [
            'includes' => EstateInclude::all(),
            'deal_types' => DealTypes::cases(),
            'estate_types' => EstateType::all(),
            'price_periods' => EstatePeriods::cases(),
            'countries' => Estate::where('status', 'Активно')->distinct()->pluck('country'),
            'towns' => Estate::where('status', 'Активно')->distinct()->pluck('town'),
            'districts' => Estate::where('status', 'Активно')->distinct()->pluck('district'),
        ];
        return view('estate_filters', $data);
    }

    public function store(Request $request): void
    {
        $request->validate(EstateFiltersData::rules());
        $data = EstateFiltersData::fromRequest($request);

        SaveUserFiltersAction::execute($data);
    }
}
