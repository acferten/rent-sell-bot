<?php

namespace Domain\Estate\Enums;

enum EstateTypes: string
{
    case house = 'Дом';
    case apartments = 'Апартаменты';
    case villa = 'Вилла';
}
