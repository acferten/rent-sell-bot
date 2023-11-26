<?php

namespace Domain\Estate\Models;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Events\EstateUpdatedEvent;
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
use Illuminate\Support\Collection;
use Lacodix\LaravelModelFilter\Traits\HasFilters;
use Spatie\LaravelData\WithData;

/**
 * @property int $id
 *
 * @property string $description
 * @property int $bathrooms
 * @property int $bedrooms
 * @property int $conditioners
 * @property int $price
 *
 * @property string $video
 * @property string $main_photo
 * @property string $check_photo
 *
 * @property string $status
 * @property string $deal_type
 *
 * @property string $latitude
 * @property string $longitude
 * @property string $country
 * @property string $state
 * @property string $county
 * @property string $town
 * @property string $district
 *
 * @property User $user
 * @property Type $type
 *
 * @property int $admin_message_id
 * @property int $type_id
 * @property int $user_id
 *
 * @property Collection $amenities
 * @property Collection $services
 * @property Collection $photos
 * @property Collection $reports
 *
 */
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
        'title',
        'available_date',
        'description',
        'bathrooms',
        'bedrooms',
        'conditioners',
        'price',
        'paid_with',

        'video',
        'main_photo',

        'status',
        'deal_type',

        'latitude',
        'longitude',
        'country',
        'state',
        'county',
        'town',
        'district',
        'street',
        'house_number',

        'end_date',
        'relevance_date',

        'views',
        'chattings',

        'type_id',
        'user_id',
        'admin_message_id'
    ];

    protected $dispatchesEvents = [
        'updated' => EstateUpdatedEvent::class,
    ];

    public function status(): EstateStatus
    {
        return EstateStatus::from($this->status);
    }

    public function geoposition(): string
    {
        return implode(", ", array_filter(array($this->country, $this->state, $this->county, $this->town, $this->district, $this->street, $this->house_number)));
    }

    public function getGoogleLink(): string
    {
        return "https://maps.google.com/?q={$this->latitude},{$this->longitude}";
    }

    // Relations
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class);
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
}
