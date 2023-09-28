<?php

namespace Domain\Estate\Enums;

enum EstateCallbacks: string
{
    case CreateEstate = 'Разместить объект недвижимости для аренды или продажи';
    case GetRentEstates = 'Найти жилье в аренду';

    case GetSellEstates = 'Купить жилье';
}
