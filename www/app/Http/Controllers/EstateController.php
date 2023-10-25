<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Domain\Estate\Actions\CloseIrrelevantEstatesAction;
use Domain\Estate\Actions\UpsertEstateAction;
use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstatePeriods;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstateInclude;
use Domain\Estate\Models\EstatePrice;
use Domain\Estate\Models\EstateType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use SergiX44\Nutgram\Exception\InvalidDataException;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Inline\InlineQueryResultArticle;
use SergiX44\Nutgram\Telegram\Types\Input\InputTextMessageContent;

class EstateController extends Controller
{
    public function index()
    {
    }

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

    public function store(Request $request): void
    {
        $bot = app(Nutgram::class);

        $request->validate(EstateData::rules());

        try {
            $webappData = $bot->validateWebAppData($request->input('initData'));
        } catch (InvalidDataException) {
            Log::debug('initData error');
        }

        $data = EstateData::fromRequest($request);
        UpsertEstateAction::execute($data);

        $result = new InlineQueryResultArticle(1, 'Успех',
            new InputTextMessageContent("Основные данные первого шага успешно переданы! 🥳"));

        $bot->answerWebAppQuery($webappData->query_id, $result);
    }

    public function show(Estate $estate)
    {
        //
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
            'estate_photos' => $estate->photos->map(fn($photo) => $photo->photo),
            'estate_includes' => $estate->includes->map(fn($include) => $include->title),
        ];
        return view('update_estate_form', $data);
    }

    public function update(Request $request, Estate $estate)
    {
        $this->store($request);
    }

    public function destroy(Estate $estate)
    {
        $estate->delete();
    }
}
