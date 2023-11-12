<?php

namespace Domain\Shared\Models\Actor;

use Database\Factories\UserFactory;
use Domain\Estate\DataTransferObjects\EstateFiltersData;
use Domain\Estate\Models\Estate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $phone
 * @property string $filters
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'username',
        'phone',
        'filters'
    ];

    protected static function newFactory()
    {
        return app(UserFactory::class);
    }

    public function estates(): HasMany
    {
        return $this->hasMany(Estate::class);
    }

    public function getFilters(): EstateFiltersData
    {
        return EstateFiltersData::from(json_decode($this->filters));
    }

    public function getTelegramUrl(): string
    {
        return 'https://t.me/' . $this->username;
    }
}
