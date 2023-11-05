<?php

namespace Domain\Estate\Enums;

enum CancelReasons: string
{
    case highPrice = 'Высокая цена';
    case test = 'Тестировал(а)';
}
