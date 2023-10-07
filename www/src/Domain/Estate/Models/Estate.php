<?php

namespace Domain\Estate\Models;

use Domain\Estate\Enums\EstateStatus;
use Domain\Shared\Models\Actor\User;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Models\Geoposition\Geoposition;
use Domain\Shared\Models\Reports\Report;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
    protected $fillable = [
        'description',
        'bathrooms',
        'bedrooms',
        'conditioners',
        'views',
        'chattings',
        'video_review',
        'status',
        'deal_type',
        'house_type_id',
        'user_id',
        'geoposition_id',
        'country',
        'town',
        'district',
        'street',
    ];

    public function status(): EstateStatus
    {
        return EstateStatus::from($this->status);
    }

    // Relations
    public function geoposition(): BelongsTo
    {
        return $this->belongsTo(Geoposition::class);
    }

    public function includes(): BelongsToMany
    {
        return $this->belongsToMany(EstateInclude::class, 'estate_includes', 'estate_id', 'include_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(EstatePhoto::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(EstatePrice::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(EstateType::class);
    }
}
