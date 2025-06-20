<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('inviteUser')
                ->label('Invite User')
                ->icon('heroicon-o-envelope')
                ->url(fn(): string => static::getResource()::getUrl('invite')),

            Actions\Action::make('createUser')
                ->label('Create User')
                ->icon('heroicon-o-plus')
                ->url(fn(): string => static::getResource()::getUrl('create'))
                ->visible(fn() => auth()->user()->hasPermissionTo('edit_user_profile'))
        ];
    }
}
