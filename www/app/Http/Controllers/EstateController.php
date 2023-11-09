<?php

namespace App\Http\Controllers;

use Domain\Estate\Actions\UpsertEstateAction;
use Domain\Estate\DataTransferObjects\EstateData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Types\Inline\InlineQueryResultArticle;
use SergiX44\Nutgram\Telegram\Types\Input\InputTextMessageContent;
use function Nutgram\Laravel\Support\webAppData;

class EstateController extends Controller
{
    public function store(Request $request): void
    {
        $request->validate(EstateData::rules());
        $data = EstateData::fromRequest($request);
        $estate = UpsertEstateAction::execute($data);
        Telegram::setUserData('estate_id', $estate->id, $data->user->id);

        $result = new InlineQueryResultArticle(1, 'Успех',
            new InputTextMessageContent("Данные первого шага успешно переданы! 🥳"));

        Telegram::answerWebAppQuery(webAppData()->query_id, $result);
    }

    public function update(Request $request): void
    {
        $request->validate(EstateData::rules());
        $data = EstateData::fromRequest($request);

        UpsertEstateAction::execute($data);

        $result = new InlineQueryResultArticle(1, 'Успех',
            new InputTextMessageContent("Данные первого шага успешно обновлены! 🥳"));

        Telegram::answerWebAppQuery(webAppData()->query_id, $result);
    }
}
