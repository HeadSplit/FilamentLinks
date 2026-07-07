<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\ClicksResource\RelationManagers\ClicksRelationManager;
use App\Filament\User\Resources\LinkResource\Pages;
use App\Filament\User\Resources\LinkResource\RelationManagers;
use Filament\Forms\Components\TextInput;
use App\Models\Link;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Ссылка';

    protected static ?string $pluralModelLabel = 'Cсылки';

    protected static ?string $navigationLabel = 'Ссылки';

    protected static ?string $navigationGroup = 'Ссылки';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('original_url')
                ->label('Оригинальная ссылка')
                ->helperText(new HtmlString('Введите ссылку, что желаете <strong>сократить</strong>'))
                ->hint('Ссылка формата https://google.com')
                    ->hintColor('primarys')
                ->startsWith(['http://', 'https://'])
                ->required()
                ->url()
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                Link::query()
                ->where('user_id', auth()->id())
            )
            ->columns([
                TextColumn::make('original_url')
                ->label('Оригинальная ссылка'),
                TextColumn::make('short_url')
                    ->label('Короткая ссылка')
                    ->formatStateUsing(fn ($state) => url($state))
                    ->url(fn ($record) => url($record->short_url))
                    ->openUrlInNewTab(),
                TextColumn::make('created_at')
                ->label('Дата создания'),
                TextColumn::make('clicks')
                ->label('Количество просмотров')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('original_url')
                    ->label('Оригинальная ссылка'),

                TextEntry::make('short_url')
                    ->label('Короткая ссылка')
                    ->formatStateUsing(fn ($state) => url($state))
                    ->url(fn ($record) => url($record->short_url))
                    ->openUrlInNewTab(),

                TextEntry::make('clicks')
                    ->label('Переходы'),

                TextEntry::make('created_at')
                    ->label('Создано')
                    ->dateTime(),
                TextEntry::make('clicks_count')
                    ->label('Количество переходов'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ClicksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLinks::route('/'),
            'create' => Pages\CreateLink::route('/create'),
            'view' => Pages\ViewLink::route('/{record}'),
            'edit' => Pages\EditLink::route('/{record}/edit'),
        ];
    }
}
