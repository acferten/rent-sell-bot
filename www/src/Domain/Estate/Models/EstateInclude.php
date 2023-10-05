<?php

namespace Domain\Estate\Models;

use Domain\Estate\Enums\Includes;
use Domain\Shared\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $estate_id
 * @property string $include
 */
class EstateInclude extends BaseModel
{
    protected $table = 'includes';

    protected $fillable = [
        'title'
    ];

    // Relations
    public function estates(): BelongsToMany
    {
        return $this->belongsToMany(Estate::class, 'estate_includes', 'include_id', 'estate_id');
    }
}