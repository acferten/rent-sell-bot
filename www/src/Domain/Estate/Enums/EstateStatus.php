<?php

namespace Domain\Estate\Enums;

enum EstateStatus: string
{
    case inspection = 'На осмотре';
    case active = 'Активно';
    case closed = 'Закрыто';
    case closedByOwner = 'Закрыто владельцем';
    case banned = 'Заблокировано';
    case pending = 'На модерации';
    case notFinished = 'Не заполнен';

    public function canBeChanged(): bool
    {
        return match ($this) {
            EstateStatus::inspection, EstateStatus::closedByOwner, EstateStatus::active => true,
            default => false
        };
    }
}
