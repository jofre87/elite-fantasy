<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ScrapingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeagueController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::view('league/join', 'unirseLiga')->name('league.join');
});

Route::get('/scrape-players', [ScrapingController::class, 'scrapeQuotes']);
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth']);
require __DIR__ . '/auth.php';

// Ruta para mostrar la vista de crear liga
Route::get('/crear-liga', function () {
    return view('crearLiga');
})->name('league.create');

// Ruta para manejar el formulario de creación de liga
Route::post('/crear-liga', [LeagueController::class, 'store'])->name('league.store');