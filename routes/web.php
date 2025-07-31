<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/admin'));
Route::get('/login', fn() => redirect('/admin/login'))->name('login');

use App\Http\Controllers\ExportReportingController;

Route::get('/admin/export-reporting', [ExportReportingController::class, 'export'])
    ->name('export-reporting');
