<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AnalyticsController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/analytics')
    ->middleware('auth:sanctum')
    ->controller(AnalyticsController::class)
    ->name('analytics')
    ->group(function() {
        Route::get('/visitors','visitors');
        Route::get('/referrers_paths','referrers_paths');
        Route::get('/referrers','referrers');
        Route::get('/clicks','clicks');
        Route::get('/website_info','website_info');
    });