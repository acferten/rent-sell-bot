<?php

namespace Domain\Shared\Enums;

enum MessageText: string
{
    case StartCommandText = 'Что вы хотите?';

    case GetEstatesText = 'Вы хотите посмотреть все объявления подряд или настроить
    фильтр исходя из вашего запроса. В дальнейшем вы можете настраивать
    фильтр зайдя в Меню (синяя кнопка в нижнем левом углу)';
}
