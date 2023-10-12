<?php

namespace Domain\Estate\Enums;
enum CreateEstateText: string
{
    case FirstStepHeader = '<b>Шаг 1</b>
';
    case FirstStepDescription = 'Заполните основные данные об объекте, который Вы хотите продать или сдать в аренду.';

    case FillEstateFormText = '✍️ Заполнить форму';

    case FillEstateFormUrl = 'https://098d-176-65-60-218.ngrok-free.app/estate/create';
}
