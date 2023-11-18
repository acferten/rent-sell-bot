<?php

namespace Domain\Shared\Models;

use Domain\Estate\Models\Estate;
use Domain\Shared\Enums\Reports\ReportReasons;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $estate_id
 * @property string $reason
 */
class Report extends BaseModel
{
    public function reason(): ReportReasons
    {
        return ReportReasons::from($this->reason);
    }

    // Relations
    public function estate(): BelongsTo
    {
        return $this->belongsTo(Estate::class);
    }
}
