<?php

namespace App\Filament\Admin\Resources\ActivityResource\Pages;

use App\Filament\Admin\Resources\ActivityResource;
use Filament\Actions;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewActivity extends ViewRecord
{
    protected static string $resource = ActivityResource::class;
    public function infolist(Infolist $infolist): Infolist
    {
        clock()->info("ViewActivity::infolist", ['infolist' => $infolist, 'record' => $this->record]);
        return $infolist
        ->schema([
            TextEntry::make('description'),
            TextEntry::make('subject_type'),
            TextEntry::make('causer_type'),
            TextEntry::make('created_at'),
        ]);
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Go back')
                ->url(url()->previous())
                ->color('secondary')
                ->icon('heroicon-o-arrow-left'),
        ];
    }
}
