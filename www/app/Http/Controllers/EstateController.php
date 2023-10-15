<?php

namespace App\Http\Controllers;


use Domain\Estate\Actions\CreateEstateAction;
use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstatePeriods;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstateInclude;
use Domain\Estate\Models\EstateType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Exception\InvalidDataException;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Inline\InlineQueryResultArticle;
use SergiX44\Nutgram\Telegram\Types\Input\InputTextMessageContent;

class EstateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Estate::first()->photos;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'includes' => EstateInclude::all(),
            'deal_types' => DealTypes::cases(),
            'estate_types' => EstateType::all(),
            'price_periods' => EstatePeriods::cases()
        ];

        return view('create_estate_form', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $bot = app(Nutgram::class);

        $request->validate(EstateData::rules());

        try {
            $webappData = $bot->validateWebAppData($request->input('initData'));
        } catch (InvalidDataException) {
            Log::debug('initData error');
        }

        $data = EstateData::fromRequest($request);
        $estate = CreateEstateAction::execute($data);

        $result = new InlineQueryResultArticle(1, 'Успех',
            new InputTextMessageContent("Основные данные первого шага успешно переданы! 🥳"));

        $bot->answerWebAppQuery($webappData->query_id, $result);

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
