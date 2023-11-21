<?php

namespace Domain\Estate\DataTransferObjects;

use Domain\Estate\Enums\ReportReasons;
use Domain\Estate\Models\Estate;
use Illuminate\Http\Request;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\WithoutValidation;
use Spatie\LaravelData\Data;

class EstateReportData extends Data
{
    public function __construct(
        #[WithoutValidation]
        public readonly Estate $estate,
        #[Enum(ReportReasons::class)]
        public readonly string $report_reason,
    )
    {
    }

    public static function fromRequest(Request $request): self
    {
        return self::from([
            ...$request->all(),
            'estate' => $request->route('estate'),
        ]);
    }
}
