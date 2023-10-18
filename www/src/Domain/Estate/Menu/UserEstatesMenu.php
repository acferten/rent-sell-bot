<?php

namespace Domain\Estate\Menu;


use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstateType;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Conversations\InlineMenu;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;


class UserEstatesMenu extends InlineMenu
{
    public $estates;
    public $element;

    public function start(Nutgram $bot): void
    {
        $this->estates = Estate::where('user_id', '=', $bot->userId())
            ->where('status', '!=', EstateStatus::notFinished)
            ->get();

        if ($this->estates->isEmpty()) {
            $this->menuText( 'У вас нет объектов')->showMenu();
        }

        $this->element = 0;

        $this->clearButtons()->menuText($this->getPreview($this->estates[$this->element]),
            ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('Изменить статус', callback_data: "{$this->estates[$this->element]->id}@handleChangeStatus"));

            if (array_key_exists($this->element + 1, $this->estates->toArray())) {
                $this->addButtonRow(InlineKeyboardButton::make('Далее', callback_data: 'next@handleNext'));
            }

            $this->orNext('none')
            ->showMenu();
    }

    public function handleNext(Nutgram $bot): void
    {
        $this->element += 1;

        $this->clearButtons()->menuText($this->getPreview($this->estates[$this->element]),
            ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('Изменить статус', callback_data: "{$this->estates[$this->element]->id}@handleChangeStatus"));

        if (array_key_exists($this->element + 1, $this->estates->toArray())) {
            $this->addButtonRow(InlineKeyboardButton::make('Далее', callback_data: 'next@handleNext'));
        }

        $this->orNext('none')
            ->showMenu();
    }

    public function handleBack(): void
    {
        $this->clearButtons()->menuText($this->getPreview($this->estates[$this->element]),
            ['parse_mode' => 'html'])
            ->addButtonRow(InlineKeyboardButton::make('Изменить статус', callback_data: "{$this->estates[$this->element]->id}@handleChangeStatus"))
            ->addButtonRow(InlineKeyboardButton::make('Далее', callback_data: 'next@handleNext'))
            ->orNext('none')
            ->showMenu();
    }

    public function handleChangeStatus(Nutgram $bot): void
    {
        $this->clearButtons()->menuText("Вы хотите изменить статус объекта на",
            ['parse_mode' => 'html']);

        $this->addButtonRow(InlineKeyboardButton::make("Вернуться назад", callback_data: "back@handleBack"))
            ->orNext('none')
            ->showMenu();
    }

    public function handleChangeSelectedStatus()
    {

    }


    public function getPreview($estate): string
    {
        $data = EstateData::from($estate);
        $estate_type = EstateType::where(['id' => $data->house_type_id])->first()->title;
        $periods = implode(', ', $estate->prices->map(fn($price) => $price->period)->toArray());

        $preview = "Превью:\n" .
            "<b>Сделка:</b> {$data->deal_type->value}\n" .
            "<b>Количество спален</b>: {$data->bedrooms}\n" .
            "<b>Количество ванных комнат</b>: {$data->bathrooms}\n" .
            "<b>Количество кондиционеров</b>: {$data->conditioners}\n" .
            "<b>Включено в стоимость</b>: {$data->includes}\n" .
            "<b>Тип недвижимости:</b>:  {$estate_type}\n" .
            "<b>ID</b>:  {$estate->id}\n" .
            "<b>Описание:</b> {$data->description}\n";

        $preview .= $data->deal_type == DealTypes::rent ? "<b>Период аренды:</b> {$periods}\n<b>Цена за весь период</b>: {$data->period_price}\n"
            : "<b>Цена:</b> {$data->price}\n";

        return $preview;
    }

    public function none(Nutgram $bot)
    {
        $bot->sendMessage('Bye!');
        $this->end();
    }
}
