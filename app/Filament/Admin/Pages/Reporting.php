<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class Reporting extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.admin.pages.reporting';

    protected static ?int $navigationSort = 99;
}
