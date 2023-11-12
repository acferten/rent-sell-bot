<?php

namespace Domain\Estate\Conversations;


use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use Domain\Estate\ViewModels\GetEstateViewModel;
use Domain\Shared\Models\Actor\User;
use Illuminate\Support\Collection;
use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class GetEstatesConversation extends Conversation
{
    public Collection $estates;
    public int $element;

    public function start(Nutgram $bot): void
    {
        $this->estates = Estate::where('status', EstateStatus::active)->latest()->get();

        if ($this->estates->isEmpty()) {
            $bot->sendMessage('Нет объектов!');
        }

        $this->element = 0;
        array_key_exists($this->element + 1, $this->estates->toArray()) ?
            $this->getEstateLayout($bot) :
            $this->getLastEstateLayout($bot);
    }

    public function handleNext(Nutgram $bot): void
    {
        if (!$bot->isCallbackQuery()) {
            $this->getEstateLayout($bot);
        }

        $bot->answerCallbackQuery();
        $this->element += 1;
        array_key_exists($this->element + 1, $this->estates->toArray()) ?
            $this->getEstateLayout($bot) :
            $this->getLastEstateLayout($bot);
    }

    public function getEstateLayout(Nutgram $bot): void
    {
        $count = count($this->estates);
        $current_element = $this->element + 1;
        $estate = $this->estates[$this->element];
        $estate->update([
            'views' => $estate->views + 1
        ]);

        $preview = "<b>Объявление {$current_element} из {$count}</b>\n\n" . GetEstateViewModel::get($estate);
        $photo = fopen("photos/{$estate->main_photo}", 'r+');

        $bot->sendPhoto(photo: InputFile::make($photo), caption: $preview, parse_mode: 'html',
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make('🔍 Посмотреть подробнее',
                    web_app: new WebAppInfo(env('NGROK_SERVER') . "/estate/{$estate->id}")))
                ->addRow(InlineKeyboardButton::make('🥸 Написать владельцу', url: $estate->user->getTelegramUrl()))
                ->addRow(InlineKeyboardButton::make('😡 Пожаловаться', callback_data: 'report ' . $estate->id))
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
                ->addRow(InlineKeyboardButton::make('😡 Пожаловаться', callback_data: 'report'))
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
