<?php

namespace Domain\Estate\Messages;

use Domain\Estate\Models\Estate;
use Domain\Estate\ViewModels\AdminEstatePreviewViewModel;
use Domain\Estate\ViewModels\GetEstateViewModel;
use Domain\Estate\ViewModels\ReportEstateViewModel;
use Nutgram\Laravel\Facades\Telegram;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\WebApp\WebAppInfo;
use function Laravel\Prompts\text;

class ReportEstateMessage
{
    public static function send(Estate $estate, string $report_reason): void
    {
        Telegram::sendMessage(
            "<b>😡 Complaint
Reason: {$report_reason}</b>\n\n" . ReportEstateViewModel::get($estate),
            env('ADMIN_CHAT_ID'),
            parse_mode: 'html',
        );
    }
}
