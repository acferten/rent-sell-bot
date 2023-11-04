<?php

namespace App\Http\Controllers;

use Domain\Estate\Actions\UpsertEstateAction;
use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Models\Estate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Exception\InvalidDataException;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Inline\InlineQueryResultArticle;
use SergiX44\Nutgram\Telegram\Types\Input\InputTextMessageContent;

class EstateController extends Controller
{
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
        $estate = UpsertEstateAction::execute($data);
        $bot->setUserData('estate_id', $estate->id, $data->user->id);

        $result = new InlineQueryResultArticle(1, 'Успех',
            new InputTextMessageContent("Данные первого шага успешно переданы! 🥳"));

        $bot->answerWebAppQuery($webappData->query_id, $result);
    }


    public function update(Request $request): void
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
            new InputTextMessageContent("Данные первого шага успешно обновлены! 🥳"));

        $bot->answerWebAppQuery($webappData->query_id, $result);
    }
}
