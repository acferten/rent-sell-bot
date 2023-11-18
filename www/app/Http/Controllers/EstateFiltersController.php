<?php

namespace App\Http\Controllers;

use Domain\Estate\Actions\SaveUserFiltersAction;
use Domain\Estate\DataTransferObjects\EstateFiltersData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstatePeriods;
use Domain\Estate\Models\Amenity;
use Domain\Estate\Models\Type;
use Domain\Estate\Models\Estate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class EstateFiltersController extends Controller
{
    public function get(): View
    {
        $data = [
            'includes' => Amenity::all(),
            'deal_types' => DealTypes::cases(),
            'estate_types' => Type::all(),
            'price_periods' => EstatePeriods::cases(),
        ];
        return view('estate_filters', $data);
    }

    public function store(Request $request)
    {
        $request->validate(EstateFiltersData::rules());
        $data = EstateFiltersData::fromRequest($request);

        return SaveUserFiltersAction::execute($data);
    }

}
