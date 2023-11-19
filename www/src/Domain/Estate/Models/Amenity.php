<?php

namespace Domain\Estate\Models;

use Domain\Estate\DataTransferObjects\AmenityData;
use Domain\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\LaravelData\WithData;

/**
 * @property int $id
 * @property int $estate_id
 * @property string $title
 */
class Amenity extends BaseModel
{
    use WithData;

    protected string $dataClass = AmenityData::class;

    protected $fillable = [
        'title'
    ];

    // Relations
    public function estates(): BelongsToMany
    {
        return $this->belongsToMany(Estate::class);
    }
}
