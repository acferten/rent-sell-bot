<?php

namespace Domain\Estate\ViewModels;

use Domain\Estate\Models\Estate;
use Domain\Shared\ViewModels\ToStringInterface;

class ReportEstateViewModel implements ToStringInterface
{
    public static function get(Estate $estate): string
    {
        return
            "Poster: #{$estate->id}\n" .
            "Created: {$estate->created_at}\n" .
            "User id: {$estate->user->id}\n" .
            "User login TG: @{$estate->user->username}\n" .
            "User poster count: {$estate->user->estates->count()}\n" .
            "Number TG: {$estate->user->phone}\n";
    }
}
