<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/reports/profit-and-loss', 'reports.profit-and-loss')->name('reports.profit-and-loss');
