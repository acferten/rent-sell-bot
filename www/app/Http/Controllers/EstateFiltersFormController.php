<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;


class EstateFiltersFormController extends Controller
{
    public function __invoke(Request $request): View
    {
        $data = [];
        return view('estate_filters', $data);
    }
}
