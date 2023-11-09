<?php

namespace Domain\Estate\Enums;

enum EstateStatus: string
{
    case inspection = 'На осмотре';
    case active = 'Активно';
    case closed = 'Закрыто';
    case banned = 'Заблокировано';
    case pending = 'На модерации';
    case notFinished = 'Не заполнен';
}
