<?php

namespace Domain\Shared\Enums\Reports;

enum ReportReasons: string
{
    case not_relevant = 'Объявление неактуально';
    case doesnt_answer = 'Владелец не отвечает';
    case rude_owner = 'Владелец невежливый';
}
