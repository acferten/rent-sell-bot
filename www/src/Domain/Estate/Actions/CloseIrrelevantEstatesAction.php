<?php

namespace Domain\Estate\Actions;

use Carbon\Carbon;
use Domain\Estate\Enums\EstateStatus;
use Domain\Estate\Models\Estate;
use Domain\Estate\ViewModels\PreviewCreatedEstateViewModel;
use Nutgram\Laravel\Facades\Telegram;

class CloseIrrelevantEstatesAction
{
    public function __invoke(): void
    {
        $estates = Estate::where('relevance_date', '<=', Carbon::now()->subDays(2)->translatedFormat('Y-m-d'))->get();

        $estates->each(function ($estate) {
            $preview = PreviewCreatedEstateViewModel::get($estate);

            $estate->update(['status' => EstateStatus::closed]);
            Telegram::sendMessage("Объект был закрыт, так как Вы долго не подтверждали его актуальность.\nДля того, чтобы вернуть его в поиск, можете ввести команду /myobjects и изменить статус на Активно.\n\n{$preview}",
                $estate->user_id, parse_mode: 'html');
        });
    }
}
