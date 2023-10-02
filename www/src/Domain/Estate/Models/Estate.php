<?php

namespace Domain\Estate\Models;

use Domain\Estate\Enums\DealTypes;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Enums\EstateTypes;
use Domain\Shared\Models\Actor\User;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Models\Geoposition\Geoposition;
use Domain\Shared\Models\Reports\Report;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $user_id
 * @property string $estate_type
 * @property string $deal_type
 * @property integer $geoposition_id
 * @property string $video_review
 * @property string $description
 * @property string $status
 * @property integer $views
 * @property integer $chattings
 * @property integer $bedrooms
 * @property integer $bathrooms
 * @property integer $conditioners
 */
class Estate extends BaseModel
{
    public function status(): EstateStatus
    {
        return EstateStatus::from($this->status);
    }

    // Relations
    public function geoposition(): BelongsTo
    {
        return $this->belongsTo(Geoposition::class);
    }

    public function includes(): HasMany
    {
        return $this->hasMany(EstateInclude::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(EstatePhotos::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(EstatePrice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
