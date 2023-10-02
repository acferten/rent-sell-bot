<?php

namespace Domain\Estate\Models;

use Domain\Estate\Enums\Includes;
use Domain\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $estate_id
 * @property string $include
 */
class EstateInclude extends BaseModel
{
    public function include(): Includes
    {
        return Includes::from($this->include);
    }

    // Relations
    public function estate(): BelongsTo
    {
        return $this->belongsTo(Estate::class);
    }
}
