<?php

namespace Domain\Estate\Models;

use Domain\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $estate_id
 * @property string $title
 */
class Amenity extends BaseModel
{
    protected $fillable = [
        'title'
    ];

    // Relations
    public function estates(): BelongsToMany
    {
        return $this->belongsToMany(Estate::class);
    }
}
