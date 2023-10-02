<?php

namespace Domain\Estate\Enums;

enum EstateStatus: string
{
    case inspection = 'На осмотре';
    case active = 'Активно';
    case closed = 'Закрыто';
}
