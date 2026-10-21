<?php
namespace App\Filament\Widgets;
use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
class LeadsTodayWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        $start = Carbon::now('Europe/Moscow')->startOfDay()->utc();
        $count = Lead::query()
            ->where('type', 'price_request')
            ->where('created_at', '>=', $start)
            ->count();
        return [
            Stat::make('Заявки сегодня', (string) $count)
                ->description('с 00:00 по Москве')
                ->color('success'),
        ];
    }
}
