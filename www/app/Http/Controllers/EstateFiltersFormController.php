<?php

namespace App\Http\Controllers;

use Domain\Estate\Models\Estate;
use Illuminate\Http\Request;
use Illuminate\View\View;


class EstateFiltersFormController extends Controller
{
    public function __invoke(Request $request): View
    {
        $data = [
            'countries' => Estate::all()->map(fn($estate) => $estate->country),
        ];

        return view('estate_filters', $data);
    }
}
