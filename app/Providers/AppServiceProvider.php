<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Redirect;
use Livewire\Livewire;
use App\Models\LigaUser;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Para vistas tradicionales (Blade o Volt)
        View::composer('*', function ($view) {
            $this->checkLigaAndRedirect();
        });

        // Para componentes Livewire (se ejecuta antes de montar cualquier componente)
        Livewire::listen('component.mount', function ($component, $params) {
            $this->checkLigaAndRedirect();
        });

        View::share('appTitle', env('APP_TITLE', 'ELITEFANTASY'));
    }

    private function checkLigaAndRedirect()
    {
        $user = Auth::user();

        if (!$user) {
            return; // No está logueado, no hacemos nada
        }

        $estaEnLiga = LigaUser::where('user_id', $user->id)->exists();

        $allowedRoutes = [
            'league.create',
            'league.store',
            'league.join',
            'league.code',
            'crear.form',
            'unir.form',
            'logout',
            'login',
            'register',
            null,
            'settings.profile',
            'settings.password',
            'settings.appearance',
        ];

        $currentRoute = Route::current()?->getName();

        if (!$estaEnLiga && !in_array($currentRoute, $allowedRoutes)) {
            Redirect::to(route('league.create'))->send();
        }
    }
}
