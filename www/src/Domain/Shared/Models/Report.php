<?php

namespace Domain\Shared\Models;

use Domain\Estate\Models\Estate;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $estate_id
 * @property string $reason
 */
class Report extends BaseModel
{
    // Relations
    public function estate(): BelongsTo
    {
        return $this->belongsTo(Estate::class);
    }
}
