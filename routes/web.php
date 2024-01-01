<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//My controller
use App\Http\Controllers\LinksController;

/*Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});*/

Route::get('/legal',function () {
    return view('impressum');
});

Route::controller(LinksController::class)->group(function () {
    Route::get('/','index');
    Route::get('/go/{id}','redirect');
});

Route::middleware(['auth', 'verified','cookies'])->group(function () {
    Route::get('/dashboard',function () { return Inertia::render('Dashboard'); })->name('dashboard');
    Route::get('/links',function () { return Inertia::render('Links'); })->name('links');
    Route::get('/logs',function () { return Inertia::render('Logs'); })->name('logs');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
