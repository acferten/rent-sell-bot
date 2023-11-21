<?php

namespace Domain\Estate\Actions;

use Domain\Estate\DataTransferObjects\EstateReportData;
use Domain\Estate\Messages\ReportEstateMessage;
use Domain\Shared\Models\Report;

class ReportEstateAction
{
    public static function execute(EstateReportData $data)
    {
        ReportEstateMessage::send($data->estate, $data->report_reason);

        return $data->estate->reports()
            ->save(new Report([
                'reason' => $data->report_reason
            ]));
    }
}
