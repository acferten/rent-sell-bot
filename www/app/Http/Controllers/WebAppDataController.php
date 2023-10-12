<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use SergiX44\Nutgram\Exception\InvalidDataException;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Inline\InlineQueryResultArticle;
use SergiX44\Nutgram\Telegram\Types\Input\InputTextMessageContent;

class WebAppDataController extends Controller
{
    public function __invoke(Request $request): void
    {
        $bot = app(Nutgram::class);

        try {
            $webappData = $bot->validateWebAppData($request->input('initData'));
        } catch (InvalidDataException) {
        }

        $result = new InlineQueryResultArticle(1, 'Name', new InputTextMessageContent('aaaaaaaaaaaaaa'));
        $bot->answerWebAppQuery($webappData->query_id, $result);
    }
}
