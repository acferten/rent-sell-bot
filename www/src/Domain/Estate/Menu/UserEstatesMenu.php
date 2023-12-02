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
            ->where('status', '!=', EstateStatus::notFinished->value)
            ->where('status', '!=', EstateStatus::deletedDraft->value)
            ->latest()
            ->get();

        if ($this->estates->isEmpty()) {
            $this->menuText('У вас нет объектов')->showMenu();
        }

        $this->element = 0;
        $this->getEstateLayout($bot);
    }

    public function handleNext(Nutgram $bot): void
    {
        $this->element += 1;
        $this->getEstateLayout($bot);
    }

    public function handleBack(Nutgram $bot): void
    {
        $this->element -= 1;
        $this->getEstateLayout($bot);
    }

    public function returnBack(Nutgram $bot): void
    {
        $this->getEstateLayout($bot);
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

            EstateStatus::closedByOwner->value =>
            $this->addButtonRow(InlineKeyboardButton::make(
                EstateStatus::inspection->value,
                callback_data: "inspection,{$bot->callbackQuery()->data}@handleChangeSelectedStatus")
            )
                ->addButtonRow(InlineKeyboardButton::make(EstateStatus::active->value,
                    callback_data: "active,{$bot->callbackQuery()->data}@handleChangeSelectedStatus"))
        };

        $this->addButtonRow(InlineKeyboardButton::make("◀️ Вернуться назад", callback_data: "back@returnBack"))
            ->orNext('none')
            ->showMenu();
    }

    public function handleChangeSelectedStatus(Nutgram $bot): void
    {
        $updateInfo = explode(",", $bot->callbackQuery()->data);
        $estate = Estate::findOrFail($updateInfo[1]);

        match ($updateInfo[0]) {
            'active' => $estate->update([
                'status' => EstateStatus::active->value
            ]),

            'inspection' => $estate->update([
                'status' => EstateStatus::inspection->value
            ]),

            'closed' => $estate->update([
                'status' => EstateStatus::closedByOwner->value
            ]),
        };

        $this->getEstateLayout($bot);
    }

    public function getEstateLayout(Nutgram $bot): void
    {
        $estate = $this->estates[$this->element];
        $count = count($this->estates);
        $element = $this->element + 1;

        $bot->setUserData('user_posters_message_id', $this->messageId);

        $preview = "<b>Объявление {$element} из {$count}</b>\n\n" . UserEstateViewModel::get($estate);

        $this->clearButtons()->menuText($preview, ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('👀 Посмотреть подробнее',
                web_app: new WebAppInfo(env('NGROK_SERVER') . "/estates/{$estate->id}")));

        if ($estate->status()->canBeChanged()) {
            $this->addButtonRow(InlineKeyboardButton::make('✍️ Изменить статус',
                callback_data: "{$estate->id}@handleChangeStatus"))
                ->addButtonRow(InlineKeyboardButton::make('✍️ Редактировать',
                    web_app: new WebAppInfo(env('NGROK_SERVER') . "/estates/{$estate->id}/user-update")));
        }

        if (array_key_exists($this->element - 1, $this->estates->toArray())) {
            $this->addButtonRow(InlineKeyboardButton::make('◀️ Назад', callback_data: 'next@handleBack'));
        }

        if (array_key_exists($this->element + 1, $this->estates->toArray())) {
            $this->addButtonRow(InlineKeyboardButton::make('▶️ Далее', callback_data: 'next@handleNext'));
        }

        $this->orNext('none')
            ->showMenu();
    }

    public function none(Nutgram $bot): void
    {
        $bot->sendMessage('Вы вышли из просмотра списка ваших объектов.');
        $this->end();
    }
}
