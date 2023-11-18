<?php

namespace Domain\Estate\Models;

use Domain\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 */
class Type extends BaseModel
{
    protected $fillable = [
        'title'
    ];

    public function estates(): HasMany
    {
        return $this->hasMany(Estate::class);
    }
}
