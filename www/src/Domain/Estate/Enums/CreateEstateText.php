<?php

namespace Domain\Estate\Enums;
enum CreateEstateText: string
{
    case FirstStepHeader = '<b>Шаг 1 из 3</b>
';
    case FirstStepDescription = 'Заполните основные данные об объекте, который Вы хотите продать или сдать в аренду.';

    case FillEstateFormText = '✍️ Заполнить форму';

    case FillEstateFormUrl = 'https://f3b5-77-106-104-230.ngrok-free.app/estate/create';
    case FillEstateFormUrlEdit = 'https://f3b5-77-106-104-230.ngrok-free.app/estate/';
}
