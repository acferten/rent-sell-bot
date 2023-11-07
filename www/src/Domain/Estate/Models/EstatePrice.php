<?php

namespace Domain\Estate\Models;

use Domain\Estate\DataTransferObjects\RentPeriodsData;
use Domain\Estate\Enums\EstatePeriods;
use Domain\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $estate_id
 * @property int $price
 * @property string $period
 */
class EstatePrice extends BaseModel
{
    protected string $dataClass = RentPeriodsData::class;

    protected $table = 'estate_prices';

    protected $fillable = [
        'price',
        'period',
        'estate_id'
    ];

    public function period(): EstatePeriods
    {
        return EstatePeriods::from($this->period);
    }

    // Relations
    public function estate(): BelongsTo
    {
        return $this->belongsTo(Estate::class);
    }
}
