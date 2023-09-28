<?php

namespace Domain\Estate\Enums;

enum EstateCallbacks: string
{
    case CreateEstate = '👨‍💼 Сдать жильё';
    case GetRentEstates = '🕵 Найти жильё';

    case GetSellEstates = 'Купить жилье';
}
