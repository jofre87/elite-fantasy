<x-layouts.app :title="__('Dashboard')">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 text-black p-4 bg-blue-900">

        {{-- Panel Superior dentro de un recuadro --}}
        <div class="lg:col-span-4 bg-purple-700 p-4 rounded-xl flex justify-between items-center border-2 border-purple-800">
            <div>
                <p class="text-sm">Nombre de equipo</p>
                <p class="text-xl font-bold">1º | {{ $ligaUsers->first()->user->puntos ?? '0' }} pts |
                    {{ number_format($ligaUsers->first()->saldo, 2, ',', '.') }}€</p>
            </div>
            <div>
                <p class="text-sm text-right">Valor de tu equipo:</p>
                <p class="text-xl font-bold text-right">65M€</p> {{-- Ajusta esto según sea necesario --}}
            </div>
        </div>

        {{-- Clasificación LigaUser reorganizada en lugar adecuado --}}
        <div class="lg:col-span-1 bg-white dark:bg-neutral-800 p-4 rounded-xl shadow mt-6 border border-purple-300">
            <h2 class="font-bold text-center text-black bg-purple-700 p-2 rounded-t">CLASIFICACIÓN</h2>
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-200 mt-2 table-fixed">
                <thead class="uppercase bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-200">
                    <tr>
                        <th class="px-2 py-1 w-10 text-center">#</th>
                        <th class="px-2 py-1 w-40">Usuario</th>
                        <th class="px-2 py-1 w-16 text-center">Puntos</th>
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

        {{-- Jornada (Estática por ahora) --}}
        <div class="bg-gray-100 text-black p-4 rounded-xl">
            <h2 class="font-bold text-center bg-purple-700 text-black p-2 rounded">Jornada 24</h2>
            <div class="grid grid-cols-3 gap-2 mt-2">
                @foreach (range(1, 9) as $i)
                    <div class="bg-black text-black p-2 rounded text-center text-xs">24/3</div>
                @endforeach
            </div>
        </div>

        {{-- Clasificación LaLiga (Ejemplo Estático) --}}
        <div class="lg:col-span-1 bg-gray-100 text-black p-4 rounded-xl">
            <h2 class="font-bold text-center bg-purple-700 text-black p-2 rounded">Clasificación LaLiga</h2>
            <ul class="mt-2 space-y-1 text-sm">
                @foreach (range(1, 20) as $i)
                    <li class="flex justify-between border-b pb-1">
                        <span>{{ $i }}. Equipo</span>
                        <span>{{ rand(10, 90) }} pts</span>
                    </li>
                @endforeach
            </ul>
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
