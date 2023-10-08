<?php

namespace Domain\Estate\Actions;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Models\Estate;
use Domain\Estate\Models\EstatePrice;

class CreateEstateAction
{
    public static function execute(EstateData $data)
    {
        $estate = Estate::create([
            ...$data->all(),
            'user_id' => 1,

        ]);

        $estate->includes()->syncWithPivotValues($data->includes->toCollection()->pluck('id'), ['estate_id' => $estate->id]);

        $data->period ?? EstatePrice::create([]); //TODO: ОТДЕЛЬНЫЙ DTO И ВЛОЖИТЬ ЕГО В ESTATE DATA

        return back()->with('success', 'Создано.');

//        return $estate->refresh();
    }
}
