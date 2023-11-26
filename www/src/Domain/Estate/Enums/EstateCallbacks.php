<?php

namespace Domain\Estate\Enums;

enum  EstateCallbacks: string
{
    case StartCreateEstate = '👨‍💼 Разместить объект';
    case CreateEstate = '🏡 Разместить объект';
    case CallManager = '🙋‍ Чат с менеджером';
    case StartGetEstates = '🕵 Найти жильё';
    case GetEstates = '🏡 Посмотреть все';
    case GetFilteredEstates = '🎯 Настроить фильтр';
    case GetSellEstates = 'Купить жилье';

    case GoBack = '◀️ Вернуться назад';
}
