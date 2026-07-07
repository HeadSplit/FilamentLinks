<?php

namespace App\Filament\User\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\Link;
use App\Models\Click;
use Filament\Widgets\StatsOverviewWidget\Card;

class LinkStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            BaseWidget\Card::make(
                'Всего ссылок',
                Link::where('user_id', auth()->id())->count()
            ),

            Card::make(
                'Всего переходов',
                Click::whereHas('link', function ($query) {
                    $query->where('user_id', auth()->id());
                })->count()
            ),

            Card::make(
                'Сегодня переходов',
                Click::whereHas('link', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                    ->whereDate('created_at', today())
                    ->count()
            ),
        ];
    }
}
