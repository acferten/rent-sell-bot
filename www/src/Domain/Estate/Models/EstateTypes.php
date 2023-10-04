<?php

namespace Domain\Estate\Models;

use Domain\Shared\Models\BaseModel;


/**
 * @property int $id
 * @property int $estate_id
 * @property int $price
 * @property string $period
 */
class EstateTypes extends BaseModel
{
    protected $fillable = [
        'title'
    ];
}
