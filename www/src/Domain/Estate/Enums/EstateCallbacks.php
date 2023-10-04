<?php

namespace Domain\Estate\Enums;

enum EstateCallbacks: string
{
    case StartCreateRentEstate = '👨‍💼 Сдать жильё';
    case CreateRentEstate = '🏡 Разместить объект';
    case CallManager = '🙋‍ Написать менеджеру';
    case StartGetEstates = '🕵 Найти жильё';
    case GetEstates = '🏡 Посмотреть все';
    case GetFilteredEstates = '🎯 Настроить фильтр';
    case GetSellEstates = 'Купить жилье';

    case GoBack = '◀️ Вернуться назад';
}
