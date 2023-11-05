<?php

namespace Domain\Estate\Menu;

use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use Domain\Estate\ViewModels\UserEstateViewModel;
use Illuminate\Support\Collection;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;


class UserEstatesMenu extends InlineMenu
{
    public Collection $estates;
    public int $element;

    public function start(Nutgram $bot): void
    {
        $this->estates = Estate::where('user_id', '=', $bot->userId())
            ->where('status', '!=', EstateStatus::notFinished)->get();

        if ($this->estates->isEmpty()) {
            $this->menuText('У вас нет объектов')->showMenu();
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

    public function returnBack(): void
    {
        $this->getEstateLayout();
    }

    public function handleChangeStatus(Nutgram $bot): void
    {
        $this->clearButtons()->menuText("Вы хотите изменить статус объекта на",
            ['parse_mode' => 'html']);

        match (Estate::where(['id' => $bot->callbackQuery()->data])->first()->status) {
            EstateStatus::inspection->value => $this->addButtonRow(InlineKeyboardButton::make(EstateStatus::active->value,
                callback_data: "active,{$bot->callbackQuery()->data}@handleChangeSelectedStatus"))
                ->addButtonRow(InlineKeyboardButton::make(EstateStatus::closed->value,
                    callback_data: "closed,{$bot->callbackQuery()->data}@handleChangeSelectedStatus")),

            EstateStatus::active->value => $this->addButtonRow(InlineKeyboardButton::make(EstateStatus::closed->value,
                callback_data: "closed,{$bot->callbackQuery()->data}@handleChangeSelectedStatus"))
                ->addButtonRow(InlineKeyboardButton::make(EstateStatus::inspection->value,
                    callback_data: "inspection,{$bot->callbackQuery()->data}@handleChangeSelectedStatus")),

            EstateStatus::closed->value => $this->addButtonRow(InlineKeyboardButton::make(EstateStatus::inspection->value,
                callback_data: "inspection,{$bot->callbackQuery()->data}@handleChangeSelectedStatus"))
                ->addButtonRow(InlineKeyboardButton::make(EstateStatus::active->value,
                    callback_data: "active,{$bot->callbackQuery()->data}@handleChangeSelectedStatus")),
        };

        $this->addButtonRow(InlineKeyboardButton::make("Вернуться назад", callback_data: "back@returnBack"))
            ->orNext('none')
            ->showMenu();
    }

    public function handleChangeSelectedStatus(Nutgram $bot): void
    {
        $updateInfo = explode(",", $bot->callbackQuery()->data);

        match ($updateInfo[0]) {
            'active' => Estate::where(['id' => $updateInfo[1]])->update([
                'status' => EstateStatus::active->value
            ]),

            'inspection' => Estate::where(['id' => $updateInfo[1]])->update([
                'status' => EstateStatus::inspection->value
            ]),

            'closed' => Estate::where(['id' => $updateInfo[1]])->update([
                'status' => EstateStatus::closed->value
            ]),
        };

        $this->start($bot);
    }

    public function getEstateLayout(): void
    {
        $count = count($this->estates);
        $element = $this->element + 1;
        $preview = "<b>Объявление {$element} из {$count}</b>\n\n" . UserEstateViewModel::get($this->estates[$this->element]);

        $this->clearButtons()->menuText($preview, ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('Посмотреть подробнее',
                web_app: new WebAppInfo(env('NGROK_SERVER') . "/estate/{$this->estates[$this->element]->id}")));

        if ($this->estates[$this->element]->status != EstateStatus::pending->value) {
            $this->addButtonRow(InlineKeyboardButton::make('Изменить статус',
                callback_data: "{$this->estates[$this->element]->id}@handleChangeStatus"));
        }

        if (array_key_exists($this->element + 1, $this->estates->toArray())) {
            $this->addButtonRow(InlineKeyboardButton::make('Далее', callback_data: 'next@handleNext'));
        }

        if (array_key_exists($this->element - 1, $this->estates->toArray())) {
            $this->addButtonRow(InlineKeyboardButton::make('Назад', callback_data: 'next@handleBack'));
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
