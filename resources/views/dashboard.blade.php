<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            @foreach ($players as $player)
                @if ($player->equipo_id == 3) <!-- Verifica si el jugador pertenece al equipo con ID 28 -->
                    <div
                        class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 bg-white dark:bg-neutral-800">
                        <div class="flex items-center gap-4">
                            <img class="w-16 h-16 rounded-full" src="{{ $player->imagen }}" alt="{{ $player->nombre }}">
                            <div>
                                <h2 class="text-lg font-bold">{{ $player->nombre }}</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $player->posicion }}</p>
                                <!-- Aquí mostramos los puntos de temporada o estadísticas adicionales -->
                                <p class="text-sm text-gray-900 dark:text-gray-100">Puntos:
                                    {{ $player->estadisticasTemporada->puntos_totales ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Otros bloques de contenido para la vista -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>

        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts.app>


<!--
CMD:
cd C:\xampp\htdocs\elite-fantasy
node y composer
librerias necesarias: composer require symfony/browser-kit symfony/http-client symfony/dom-crawler
npm install
npm run build


php artisan serve
http://localhost:8000/dashboard
http://localhost:8000/scrape-players
-->