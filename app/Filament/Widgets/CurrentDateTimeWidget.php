<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\Widget;

class CurrentDateTimeWidget extends Widget
{
    protected static string $view = 'filament.widgets.current-date-time-widget';

    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        // Set locale ke Bahasa Indonesia
        Carbon::setLocale('id');

        // Format tanggal dan waktu dalam bahasa Indonesia
        $currentDateTime = Carbon::now()->translatedFormat('l, d F Y');

        return [
            'currentDateTime' => $currentDateTime,
        ];
    }
}
