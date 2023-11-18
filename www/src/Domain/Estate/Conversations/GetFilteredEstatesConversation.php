<?php

namespace Domain\Estate\Conversations;

use Domain\Estate\Actions\GetFilteredEstatesAction;
use Domain\Estate\ViewModels\GetEstateViewModel;
use Domain\Shared\Models\User;
use Illuminate\Support\Collection;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;
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
                        web_app: new WebAppInfo(env('NGROK_SERVER') . "/estate/filters"))
                    )
                    ->addRow(InlineKeyboardButton::make('🚀 Запустить поиск',
                        callback_data: "start search")
                    )
            );
            return;
        }

        $this->estates = GetFilteredEstatesAction::execute($user->getFilters())->get();

        if ($this->estates->isEmpty()) {
            $bot->sendMessage('Нет объектов!');
            $this->end();
            return;
        }

        $this->element = 0;
        $this->getEstateLayout($bot);
    }

    public function handleNext(Nutgram $bot): void
    {
        $bot->callbackQuery()->data;
        $bot->answerCallbackQuery();
        $this->element += 1;

        if (array_key_exists($this->element + 1, $this->estates->toArray())) {
            $this->getEstateLayout($bot);
        } else {
            $this->getLastEstateLayout($bot);
        }
    }

    public function getEstateLayout(Nutgram $bot): void
    {
        $count = count($this->estates);
        $element = $this->element + 1;
        $estate = $this->estates[$this->element];
        $estate->update([
            'views' => $estate->views + 1
        ]);

        $preview = "<b>Объявление {$element} из {$count}</b>\n\n" . GetEstateViewModel::get($estate);
        $photo = fopen("photos/{$estate->main_photo}", 'r+');

        $bot->sendPhoto(photo: InputFile::make($photo), caption: $preview, parse_mode: 'html',
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make('🔍 Посмотреть подробнее',
                    web_app: new WebAppInfo(env('NGROK_SERVER') . "/estate/{$estate->id}")))
                ->addRow(InlineKeyboardButton::make('🥸 Написать владельцу', url: $estate->user->getTelegramUrl()))
                ->addRow(InlineKeyboardButton::make('➡ Следующее объявление', callback_data: 'next'))
        );

        $this->next('handleNext');
    }

    public function getLastEstateLayout(Nutgram $bot): void
    {
        $count = count($this->estates);
        $element = $this->element + 1;
        $estate = $this->estates[$this->element];
        $estate->update([
            'views' => $estate->views + 1
        ]);

        $preview = "<b>Объявление {$element} из {$count}</b>\n\n" . GetEstateViewModel::get($estate);
        $photo = fopen("photos/{$estate->main_photo}", 'r+');

        $bot->sendPhoto(photo: InputFile::make($photo), caption: $preview, parse_mode: 'html',
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make('🔍 Посмотреть подробнее',
                    web_app: new WebAppInfo(env('NGROK_SERVER') . "/estate/{$estate->id}")))
                ->addRow(InlineKeyboardButton::make('🥸 Написать владельцу', url: $estate->user->getTelegramUrl()))
        );
        $this->end();
    }

    public function none(Nutgram $bot): void
    {
        $bot->sendMessage('Bye!');
        $this->end();
    }
}
