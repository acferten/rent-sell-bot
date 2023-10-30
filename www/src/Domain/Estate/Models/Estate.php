<?php

namespace Domain\Estate\Models;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\EstateStatus;
use Domain\Shared\Models\Actor\User;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Models\Reports\Report;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Mehradsadeghi\FilterQueryString\FilterQueryString;

/**
 * @property string $id
 * @property string $user_id
 * @property string $estate_type
 * @property string $deal_type
 * @property string $video
 * @property string $description
 * @property string $status
 * @property integer $views
 * @property integer $chattings
 * @property integer $bedrooms
 * @property integer $bathrooms
 * @property integer $conditioners
 * @property string $latitude
 * @property string $longitude
 * @property int|string $house_number
 * @property EstateType $type
 * @property Collection $photos
 * @property Collection $includes
 */
class Estate extends BaseModel
{
    use FilterQueryString;

    protected string $dataClass = EstateData::class;

    protected $fillable = [
        'description',
        'bathrooms',
        'bedrooms',
        'conditioners',
        'views',
        'chattings',
        'video',
        'main_photo',
        'status',
        'deal_type',
        'house_type_id',
        'user_id',
        'country',
        'state',
        'county',
        'town',
        'district',
        'street',
        'price',
        'latitude',
        'longitude',
        'end_date',
        'house_number',
        'relevance_date'
    ];

    protected $filters = [
        'in',
        'sort'
    ];

    public function status(): EstateStatus
    {
        return EstateStatus::from($this->status);
    }

    // Relations
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
        return $this->belongsTo(EstateType::class, 'house_type_id', 'id');
    }

    public function geoposition(): string
    {
        return "$this->country, $this->town, $this->district, $this->street $this->house_number";
    }
}
