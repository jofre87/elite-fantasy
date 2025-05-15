<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ScrapingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LeagueController;
use App\Http\Controllers\UnirteLiga;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\AlineacionController;
use App\Http\Controllers\JornadaController;

Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return redirect()->route('dashboard');
    })->name('home');
    // Ruta de bienvenida redirige directamente al dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

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
    Route::get('/scrape-players', [ScrapingController::class, 'scrapePlayers']);

    Route::post('/comprar/{jugadorId}', [MarketController::class, 'comprarJugador'])->name('jugador.comprar');

    // Ruta para ver la alineación
    Route::get('/alineacion', [AlineacionController::class, 'index'])->name('alineacion.index');

    // Ruta para intercambiar jugadores
    Route::post('/alineacion/intercambiar', [AlineacionController::class, 'intercambiar'])->name('alineacion.intercambiar');
    Route::post('/alineacion/desalinear', [AlineacionController::class, 'desalinear'])->name('alineacion.desalinear');
    Route::post('/alineacion/vender', [MarketController::class, 'venderJugador'])->name('jugador.vender');

    //iniciar y finalizar jornada
    Route::post('/jornada/iniciar', [JornadaController::class, 'iniciar'])->name('jornada.iniciar');
    Route::post('/jornada/finalizar', [JornadaController::class, 'finalizar'])->name('jornada.finalizar');
});

// Rutas de autenticación (login, registro, etc.)
require __DIR__ . '/auth.php';
