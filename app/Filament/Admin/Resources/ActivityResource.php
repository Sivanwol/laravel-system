<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ActivityResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ActivityResource extends Resource
{
    protected static ?string $model = \Spatie\Activitylog\Models\Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                return \Spatie\Activitylog\Models\Activity::query();
            })
            ->columns([
                TextColumn::make('description'),
                TextColumn::make('subject_type'),
                TextColumn::make('causer_type'),
                TextColumn::make('created_at'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivities::route('/'),
            'view' => Pages\ViewActivity::route('/{record}'),
        ];
    }
}