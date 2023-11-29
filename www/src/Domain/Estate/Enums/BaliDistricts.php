<?php

namespace Domain\Estate\Enums;

enum BaliDistricts: string
{
    case Canggu = 'Чангу';
    case Ubud = 'Убуд';
    case Uluwatu = 'Улувату';
    case Nusa_Dua = 'Нуса Дуа';
    case Seminyak = 'Семиньяк';
    case Kuta = 'Кута';
    case Sanur = 'Санур';
    case Kerobokan = 'Керобокан';
    case Denpasar = 'Денпасар';
    case Tabanan = 'Табанан';
    case Amed = 'Амед';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
