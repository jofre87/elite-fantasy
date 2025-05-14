<x-layouts.app :title="__('Dashboard')">
    <div class="max-w-6xl mx-auto px-4 py-6">

        {{-- Panel Superior Compacto --}}
        <div
            class="bg-purple-700 text-black p-6 rounded-2xl shadow-lg flex flex-col sm:flex-row justify-between items-center border-2 border-purple-800 mb-6">
            <div class="text-center sm:text-left">
                <p class="text-sm">Usuario:</p>
                <p class="text-2xl font-bold">
                    {{ $ligaUsers->first()->user->name ?? 'Sin nombre' }}
                </p>
                <p class="text-sm mt-1">
                    {{ $ligaUsers->first()->user->puntos ?? '0' }} pts |
                    {{ number_format($ligaUsers->first()->saldo, 2, ',', '.') }} €
                </p>
            </div>
            <div class="mt-4 sm:mt-0 text-center sm:text-right">
                <p class="text-sm">Valor de tu equipo:</p>
                <p class="text-2xl font-bold">65M€</p>
            </div>
        </div>

        {{-- Contenido Principal: Clasificación + Otras tablas --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Clasificación --}}
            <div class="bg-white dark:bg-neutral-800 p-4 rounded-xl shadow border border-purple-300">
                <h2 class="font-bold text-center text-white bg-purple-700 p-2 rounded-t">CLASIFICACIÓN</h2>
                <table class="w-full text-xs mt-2 table-fixed text-gray-700 dark:text-gray-200">
                    <thead class="uppercase bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-200">
                        <tr>
                            <th class="px-2 py-1 w-8 text-center">#</th>
                            <th class="px-2 py-1 truncate">Usuario</th>
                            <th class="px-2 py-1 w-14 text-center">Puntos</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-200 dark:divide-purple-700">
                        @foreach ($ligaUsers as $index => $entry)
                            <tr class="@if ($index === 0) bg-purple-50 dark:bg-purple-700/30 font-bold @endif">
                                <td class="px-2 py-1 text-center">{{ $index + 1 }}º</td>
                                <td class="px-2 py-1 truncate">{{ $entry->user->name ?? 'Sin nombre' }}</td>
                                <td class="px-2 py-1 text-center">{{ $entry->user->puntos ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Goleadores --}}
            <div class="bg-gray-100 dark:bg-neutral-800 text-black dark:text-white p-4 rounded-xl shadow">
                <h2 class="font-bold text-center bg-purple-700 text-white p-2 rounded">Máximos Goleadores</h2>
                <ul class="mt-2 space-y-1 text-sm">
                    @foreach ($goleadores as $i => $jugador)
                        <li class="flex justify-between border-b pb-1">
                            <span>{{ $i + 1 }}. {{ $jugador->nombre }}</span>
                            <span>{{ $jugador->estadisticasTemporada->goles ?? 0 }} goles</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Clasificación LaLiga --}}
            <div class="bg-gray-100 dark:bg-neutral-800 text-black dark:text-white p-4 rounded-xl shadow">
                <h2 class="font-bold text-center bg-purple-700 text-white p-2 rounded">Clasificación LaLiga</h2>
                <ul class="mt-2 space-y-1 text-sm">
                    @foreach (range(1, 10) as $i)
                        <li class="flex justify-between border-b pb-1">
                            <span>{{ $i }}. Equipo</span>
                            <span>{{ rand(10, 90) }} pts</span>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>

        {{-- Jornada Abajo Centrado --}}
        <div class="bg-gray-100 dark:bg-neutral-800 text-black dark:text-white p-4 mt-6 rounded-xl shadow">
            <h2 class="font-bold text-center bg-purple-700 text-white p-2 rounded">Jornada 24</h2>
            <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 mt-2">
                @foreach (range(1, 9) as $i)
                    <div class="bg-black text-white p-2 rounded text-center text-xs">24/3</div>
                @endforeach
            </div>
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