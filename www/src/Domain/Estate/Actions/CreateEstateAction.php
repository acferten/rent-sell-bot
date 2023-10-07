<?php

namespace Domain\Estate\Actions;

use Domain\Estate\DataTransferObjects\EstateData;
use Domain\Estate\Models\Estate;
use Illuminate\Http\RedirectResponse;

class CreateEstateAction
{
    public static function execute(EstateData $data): RedirectResponse
    {
        Estate::create([]);

        return back()->with('success', 'We have received your message and would like to thank you for wri');
    }
}
