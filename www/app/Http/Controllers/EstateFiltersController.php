<?php

namespace App\Http\Controllers;

use Domain\Estate\Actions\SaveUserFiltersAction;
use Domain\Estate\DataTransferObjects\EstateFiltersData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstatePeriods;
use Domain\Estate\Models\Amenity;
use Domain\Estate\Models\Type;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Types\Inline\InlineQueryResultArticle;
use SergiX44\Nutgram\Telegram\Types\Input\InputTextMessageContent;
use function Nutgram\Laravel\Support\webAppData;

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

        $result = new InlineQueryResultArticle(1, 'Успех',
            new InputTextMessageContent("/estates"));

        Telegram::answerWebAppQuery(webAppData()->query_id, $result);

        return SaveUserFiltersAction::execute($data);
    }

}
