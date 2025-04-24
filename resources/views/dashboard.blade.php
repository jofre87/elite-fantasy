<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Apartado Plantilla -->
        <div class="grid auto-rows-min gap-4">
            <h2>Jugador</h2>
            @foreach ($players as $player)
                @if ($player->nombre == 'kylian mbappe')
                    <div
                        class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-800">
                        <div class="flex items-center gap-4">
                            <img class="w-16 h-16 rounded-full" src="{{ $player->imagen }}" alt="{{ $player->nombre }}">
                            <div>
                                <h2 class="text-lg font-bold">{{ $player->nombre }}</h2>
                                <p
                                    class="text-s
                                                                                                                                                                                                                                                           m text-gray-600 dark:text-gray-400">
                                    Equipo: <img class="inline w-6 h-6" src="{{ $player->equipo->escudo }}"
                                        alt="{{ $player->equipo->nombre }}">
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Posición: {{ $player->posicion }}</p>

                                <p class="text-sm text-gray-600 dark:text-gray-400">Valor:
                                    {{ number_format($player->valor_actual, 0, ',', '.') }}
                                    ({{ number_format($player->diferencia, 0, ',', '.') }})
                                </p>
                                <p class="text-sm text-gray-900 dark:text-gray-100">Puntos:
                                    {{ $player->estadisticasTemporada->puntos_totales }}
                                </p>
                                <p class="text-sm text-gray-900 dark:text-gray-100">Ultimos Puntos:
                                    {{ $player->estadisticasTemporada->racha_puntos }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</x-layouts.app>

<!--
CMD:
node y composer
librerias necesarias: composer require symfony/browser-kit symfony/http-client symfony/dom-crawler
npm install
npm run build

php artisan migrate:fresh -- importante para resetear base de datos

C:\>cd Users\usuari\Documents\GitHub\elite-fantasy
php artisan serve
http://localhost:8000/dashboard
http://localhost:8000/scrape-players
-->