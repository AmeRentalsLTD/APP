<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::redirect('/api/docs', '/api/documentation'); // l5-swagger UI
});
