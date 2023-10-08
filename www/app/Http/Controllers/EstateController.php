<?php

namespace App\Http\Controllers;


use Domain\Estate\Actions\CreateEstateAction;
use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstateInclude;
use Domain\Estate\Models\EstateType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EstateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'includes' => EstateInclude::all(),
            'deal_types' => DealTypes::cases(),
            'estate_types' => EstateType::all()
        ];

        return view('create_estate_form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(EstateData::rules());
        Log::debug('validation passed');

        $data = EstateData::fromRequest($request);
        Log::debug('data object passed');

        CreateEstateAction::execute($data);
        Log::debug('action passed');

        return to_route('estate.create')->withStatus('Created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Estate $estate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estate $estate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Estate $estate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estate $estate)
    {
        //
    }
}
