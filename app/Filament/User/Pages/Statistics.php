<?php

namespace App\Filament\User\Pages;

use App\Filament\User\Widgets\LinkStats;
use App\Models\Click;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;

class Statistics extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static string $view = 'filament.user.pages.statistics';

    protected static ?string $navigationLabel = 'Статистика';

    protected static ?string $title = 'Статистика переходов';

    protected static ?string $navigationGroup = 'Статистика';


    protected function getHeaderWidgets(): array
    {
        return [
            LinkStats::class,
        ];
    }


    public function table(Table $table): Table
    {
        return $table
            ->query(
                Click::query()
                    ->whereHas('link', function ($query) {
                        $query->where('user_id', auth()->id());
                    })
            )
            ->columns([
                Tables\Columns\TextColumn::make('link.short_url')
                    ->label('Ссылка')
                    ->formatStateUsing(fn ($state) => url($state)),

                Tables\Columns\TextColumn::make('user_ip')
                    ->label('IP адрес'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Дата перехода')
                    ->dateTime(),
            ]);
    }
}
