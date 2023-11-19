<?php

namespace Domain\Estate\Models;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Filters\Country;
use Domain\Estate\Models\Filters\County;
use Domain\Estate\Models\Filters\DealType;
use Domain\Estate\Models\Filters\State;
use Domain\Estate\Models\Filters\Town;
use Domain\Shared\Models\BaseModel;
use Domain\Shared\Models\Report;
use Domain\Shared\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Lacodix\LaravelModelFilter\Traits\HasFilters;
use Spatie\LaravelData\WithData;


class Estate extends BaseModel
{
    use HasFilters;
    use WithData;

    protected string $dataClass = EstateData::class;

    protected array $filters = [
        DealType::class,
        Country::class,
        State::class,
        County::class,
        Town::class,
    ];

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
        'type_id',
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

    public function status(): EstateStatus
    {
        return EstateStatus::from($this->status);
    }

    // Relations
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(Price::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function geoposition(): string
    {
        return implode(", ", array_filter(array($this->country, $this->state, $this->county, $this->town, $this->district, $this->street, $this->house_number)));
    }

    public function getGoogleLink(): string
    {
        return "https://maps.google.com/?q={$this->latitude},{$this->longitude}";
    }
}
