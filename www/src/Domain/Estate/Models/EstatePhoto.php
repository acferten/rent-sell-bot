<?php

namespace Domain\Estate\Models;

use Domain\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $estate_id
 * @property string $photo
 * @property Estate $estate
 */
class EstatePhoto extends BaseModel
{
    protected $table = 'estate_photos';

    protected $fillable = [
        'photo',
        'estate_id'
    ];

    public function estate(): BelongsTo
    {
        return $this->belongsTo(Estate::class);
    }
}
