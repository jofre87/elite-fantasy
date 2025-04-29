<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ScrapingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeagueController;
use App\Http\Controllers\UnirteLiga;
use App\Http\Controllers\MarketController;

Route::middleware(['auth'])->group(function () {
    // Ruta de bienvenida
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

    // Vista del dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rutas de configuración de usuario con Livewire Volt
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // Rutas de ligas
    Route::view('league/join', 'unirseLiga')->name('league.join');
    Route::get('/crear-unir', function () {
        return view('crearLiga');
    })->name('league.create');
    Route::post('/crear-unir', [LeagueController::class, 'store'])->name('league.store');
    Route::post('/liga-codigo', [UnirteLiga::class, 'store'])->name('league.code');
    Route::get('/crear', function () {
        return view('crear');
    })->name('crear.form');
    Route::get('/unirte', function () {
        return view('unirte');
    })->name('unir.form');

    Route::get('/mercado', [MarketController::class, 'index'])->name('market.index');
    // Ruta del scraping
    Route::get('/scrape-players', [ScrapingController::class, 'scrapeQuotes']);
});

// Rutas de autenticación (login, registro, etc.)
require __DIR__ . '/auth.php';
