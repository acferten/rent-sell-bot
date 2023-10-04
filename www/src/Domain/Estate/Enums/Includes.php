<?php

namespace Domain\Estate\Enums;

enum Includes: string
{
    case pool = 'Бассейн';
    case garage = 'Гараж для машины/мотоцикла';
    case rooftop = 'Крышная терраса';
    case closed_living_room = 'Закрытая жилая комната';
    case kitchen_furniture = 'Кухонный гарнитур';
    case fridge = 'Холодильник';
    case washing_machine = 'Стиральная машина';
    case oven = 'Духовка';
    case dishwasher = 'Посудомоечная машина';
    case television = 'Телевизор';
    case conditioner = 'Кондиционер';
    case wifi = 'Wi-Fi';
    case bath = 'Ванна';
    case shower_cabin = 'Душевая кабина';
    case with_animals = 'Можно с животными';
    case with_children = 'Можно с детьми';
}
