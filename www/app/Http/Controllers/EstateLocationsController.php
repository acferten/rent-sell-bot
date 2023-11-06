<?php

namespace App\Http\Controllers;

use Domain\Estate\Models\Estate;
use Illuminate\Support\Collection;

class EstateLocationsController extends Controller
{
    public function countries(): Collection
    {
        return Estate::where('status', 'Активно')
            ->distinct()
            ->pluck('country');
    }

    public function states(string $country): Collection
    {
        return Estate::where('status', 'Активно')
            ->where('country', $country)
            ->distinct()
            ->pluck('state');
    }

    public function counties(string $country, string $state): Collection
    {
        return Estate::where('status', 'Активно')
            ->where('country', $country)
            ->where('state', $state)
            ->distinct()
            ->pluck('county');
    }

    public function towns(string $country, string $state, string $county): Collection
    {
        return Estate::where('status', 'Активно')
            ->where('country', $country)
            ->where('state', $state)
            ->where('county', $county)
            ->distinct()
            ->pluck('town');
    }
}
