<?php

namespace Domain\Estate\Menu;


use Domain\Estate\Enums\CreateEstateText;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use Domain\Estate\ViewModels\GetEstateViewModel;
use Domain\Shared\Models\Actor\User;
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
        if (User::find($bot->userId())->first()->isEmpty()) {

        }
        $this->estates = Estate::where('status', EstateStatus::active)->latest()->get();

        if ($this->estates->isEmpty()) {
            $bot->sendMessage('Нет объектов!');
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
        $user_url = 'https://t.me/' . User::where('id', $estate->user_id)->first()->username;

        $bot->sendMessage($preview, parse_mode: 'html',
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make('🔍 Посмотреть подробнее',
                    web_app: new WebAppInfo(CreateEstateText::EstateUrl->value . "/{$estate->id}")))
                ->addRow(InlineKeyboardButton::make('🥸 Написать владельцу', url: "$user_url"))
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
        $user_url = 'https://t.me/' . User::where('id', $estate->user_id)->first()->username;


        $bot->sendMessage($preview, parse_mode: 'html',
            reply_markup: InlineKeyboardMarkup::make()
                ->addRow(InlineKeyboardButton::make('🔍 Посмотреть подробнее',
                    web_app: new WebAppInfo(CreateEstateText::EstateUrl->value . "/{$estate->id}")))
                ->addRow(InlineKeyboardButton::make('🥸 Написать владельцу', url: "$user_url"))
        );
        $this->end();
    }

    public function none(Nutgram $bot): void
    {
        $bot->sendMessage('Bye!');
        $this->end();
    }
}
