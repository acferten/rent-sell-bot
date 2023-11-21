<?php

namespace Domain\Estate\Enums;

enum ReportReasons: string
{
    case description = 'Не соответствует описанию';
    case price = 'Неверная цена';
    case closed = 'Уже сняли';
    case ignoring = 'Владелец не отвечает';
    case discourtesy = 'Владелец не вежливый';
}
