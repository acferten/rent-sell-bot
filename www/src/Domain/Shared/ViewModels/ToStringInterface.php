<?php

namespace Domain\Shared\ViewModels;

use Domain\Estate\Models\Estate;

interface ToStringInterface
{
    public static function get(Estate $estate): string;
}
