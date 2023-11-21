<?php

namespace App\Http\Controllers;

use Domain\Estate\Actions\ReportEstateAction;
use Domain\Estate\DataTransferObjects\EstateReportData;
use Domain\Estate\Models\Estate;


class ReportEstateController extends Controller
{
    public function __invoke(Estate $estate, EstateReportData $data)
    {
        return ReportEstateAction::execute($data);
    }
}
