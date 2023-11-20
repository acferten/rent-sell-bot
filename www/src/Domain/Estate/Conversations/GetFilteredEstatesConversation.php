<?php

namespace Domain\Estate\Conversations;

use Domain\Estate\Actions\GetFilteredEstatesAction;
use Domain\Estate\Messages\EstateCardMessage;
use Domain\Shared\Models\User;
use Illuminate\Support\Collection;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class GetFilteredEstatesConversation extends Conversation
{
    public Collection $estates;
    public int $element;

    public function start(Nutgram $bot): void
    {
        $user = User::where(['id' => $bot->userId()])->first();

        if (is_null($user) || is_null($user->filters)) {
            $bot->sendMessage(
                text: '🧐 Похоже, что Вы еще не задали настройки поиска. Можете сделать это по кнопке ниже.',
                reply_markup: InlineKeyboardMarkup::make()
                    ->addRow(InlineKeyboardButton::make('⚙ Настроить фильтр',
                        web_app: new WebAppInfo(env('NGROK_SERVER') . "/estates/filters"))
                    )
            );
            return;
        }

        $this->estates = GetFilteredEstatesAction::execute($user->getFilters())->get();

        if ($this->estates->isEmpty()) {
            $bot->sendMessage("🧐 Упс! Ничего не найдено…\nВы задали слишком узкие параметры поиска.\nПросто измените фильтр и бот покажет подходящие объявления.", parse_mode: 'html',
                reply_markup: InlineKeyboardMarkup::make()
                    ->addRow(InlineKeyboardButton::make('⚙ Настроить фильтр',
                        web_app: new WebAppInfo(env('NGROK_SERVER') . "/estates/filters"))
                    ));
            $this->end();
            return;
        }

        $this->element = 0;
        $this->getEstateLayout($bot);
    }

    public function handleNext(Nutgram $bot): void
    {
        if (!$bot->isCallbackQuery()) {
            $this->getEstateLayout($bot);
            return;
        }
        if ($bot->callbackQuery()->data == 'next') {
            $bot->answerCallbackQuery();
            $this->element += 1;

            if (array_key_exists($this->element + 1, $this->estates->toArray())) {
                $this->getEstateLayout($bot);
            } else {
                $this->getLastEstateLayout($bot);
            }
        }
    }

    public function getEstateLayout(Nutgram $bot): void
    {
        $estate = $this->estates[$this->element];

        $estate->update([
            'views' => $estate->views + 1
        ]);

        EstateCardMessage::send($estate, $bot->userId(), true);

        $this->next('handleNext');
    }

    public function getLastEstateLayout(Nutgram $bot): void
    {
        $estate = $this->estates[$this->element];

        $estate->update([
            'views' => $estate->views + 1
        ]);

        EstateCardMessage::send($estate, $bot->userId());

        $this->end();
    }

    public function none(Nutgram $bot): void
    {
        $bot->sendMessage('Bye!');
        $this->end();
    }
}
