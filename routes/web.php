<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/admin'));
Route::get('/login', fn() => redirect('/admin/login'))->name('login');
