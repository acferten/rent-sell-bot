<?php

namespace Domain\Shared\Models\Actor;

use Domain\Estate\Models\Estate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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

    public function estates(): HasMany
    {
        return $this->hasMany(Estate::class);
    }
}
