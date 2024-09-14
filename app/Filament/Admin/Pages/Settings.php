<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.pages.settings';

    public static function canAccess(): bool
    {
        return true;
    }
}
