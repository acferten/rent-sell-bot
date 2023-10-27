<?php

namespace Domain\Estate\Menu;


use Domain\Estate\Enums\CreateEstateText;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use Domain\Estate\ViewModels\EstatePreviewViewModel;
use Domain\Estate\ViewModels\FindEstateViewModel;
use Domain\Shared\Models\Actor\User;
use Illuminate\Support\Collection;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;

class GetEstatesMenu extends InlineMenu
{
    public Collection $estates;
    public int $element;

    public function start(Nutgram $bot): void
    {
        $this->estates = Estate::where('status', EstateStatus::active)->get();

        if ($this->estates->isEmpty()) {
            $this->menuText('Нет объектов')->showMenu();
        }

        $this->element = 0;
        $this->getEstateLayout();
    }

    public function handleNext(): void
    {
        $this->element += 1;
        $this->getEstateLayout();
    }

    public function handleBack(): void
    {
        $this->element -= 1;
        $this->getEstateLayout();
    }


    public function getEstateLayout(): void
    {
        $count = count($this->estates);
        $element = $this->element + 1;
        $preview = "<b>Объявление {$element} из {$count}</b>\n\n" . FindEstateViewModel::get($this->estates[$this->element]);
        $user_url = 'https://t.me/' . User::where('id', $this->estates[$this->element]->user_id)->first()->username;

        $this->clearButtons()->menuText($preview, ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('🔍 Посмотреть подробнее',
                web_app: new WebAppInfo(CreateEstateText::EstateUrl->value . "/{$this->estates[$this->element]->id}")))
            ->addButtonRow(InlineKeyboardButton::make('🥸 Написать владельцу', url: "$user_url"));

        if (array_key_exists($this->element + 1, $this->estates->toArray())) {
            $this->addButtonRow(InlineKeyboardButton::make('➡ Далее', callback_data: 'next@handleNext'));
        }

        if (array_key_exists($this->element - 1, $this->estates->toArray())) {
            $this->addButtonRow(InlineKeyboardButton::make('◀️ Назад', callback_data: 'next@handleBack'));
        }

        $this->orNext('none')
            ->showMenu();
    }

    public function none(Nutgram $bot): void
    {
        $bot->sendMessage('Bye!');
        $this->end();
    }
}
