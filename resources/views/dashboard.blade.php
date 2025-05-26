<x-layouts.app :title="__('Dashboard')">
    <div class="max-w-6xl mx-auto px-4 py-6">

        {{-- ENCABEZADO USUARIO --}}
        <div
            class="bg-blue-200 text-black p-4 rounded-2xl shadow-lg flex flex-col sm:flex-row justify-between items-center mb-6">
            <div class="text-center sm:text-left">
                <p class="text-2xl font-bold">{{ $ligaUser->user->name ?? 'Sin nombre' }}</p>
                <p class="text-sm mt-1"><strong>Puntos totales: </strong>{{ $ligaUser->puntos_totales ?? '0' }}</p>
                <p class="text-sm mt-1"><strong>Saldo: </strong>
                    {{ number_format($ligaUser->saldo, 0, ',', '.') }} €
                </p>
            </div>
            <div class="mt-4 sm:mt-0 text-center sm:text-right">
                <p class="text-sm">Valor de tu equipo:</p>
                <p class="text-2xl font-bold">{{ number_format($valorMercadoTotal, 0, ',', '.') }} €</p>
                @if ($valorMercadoDiferencia > 0)
                    <p class="text-sm text-green-600">
                        ▲ {{ number_format($valorMercadoDiferencia, 0, ',', '.') }} €
                    </p>
                @elseif ($valorMercadoDiferencia < 0)
                    <p class="text-sm text-red-600">
                        ▼ {{ number_format(abs($valorMercadoDiferencia), 0, ',', '.') }} €
                    </p>
                @else
                    <p class="text-sm">
                        {{ number_format($valorMercadoDiferencia, 0, ',', '.') }} €
                    </p>
                @endif
            </div>
        </div>

        {{-- Selector de jornada --}}
        <form method="GET" action="{{ route('dashboard') }}" class="mb-6 text-center">
            <input type="hidden" name="liga_id" value="{{ $ligaId }}">
            <label for="jornada_id" class="text-sm font-semibold mr-2">Selecciona jornada:</label>
            <select name="jornada_id" id="jornada_id" onchange="this.form.submit()" class="border rounded px-3 py-1">
                @foreach ($jornadas as $jornada)
                    <option value="{{ $jornada }}" {{ $jornada == $jornadaSeleccionada ? 'selected' : '' }}>
                        Jornada {{ $jornada }}
                    </option>
                @endforeach
            </select>
        </form>

        {{-- Contenido principal: clasificación y campo --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- CLASIFICACIÓN --}}
            <div class="bg-white dark:bg-neutral-800 p-4 rounded-xl shadow border border-purple-300">
                <h2 class="font-bold text-center text-white bg-purple-700 p-2 rounded-t">
                    CLASIFICACIÓN @isset($ligaId)
                        - Liga {{ $ligaId }}
                    @endisset
                </h2>

                @if ($ligaUsers->isEmpty())
                    <p class="text-center text-sm text-gray-500 dark:text-gray-300 mt-4">No hay usuarios en esta liga.
                    </p>
                @else
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
                                    <td class="px-2 py-1 text-center">{{ $entry->puntos_totales ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- CAMPO DE ALINEACIÓN --}}
            <div>
                <h2 class="text-lg font-semibold mb-4 text-center">Once Jornada {{ $jornadaSeleccionada }}</h2>

                <div class="rounded-xl bg-cover bg-center mx-auto"
                    style="background-image: url('{{ asset('img/campo.jpg') }}'); width: 419px; height: 612px;">
                    <div class="flex flex-col h-full">

                        @php
                            $delanteros = $alineacion->where('posicion', 'Delantero');
                            $mediocampistas = $alineacion->where('posicion', 'Mediocampista');
                            $defensas = $alineacion->where('posicion', 'Defensa');
                            $porteros = $alineacion->where('posicion', 'Portero');
                        @endphp

                        {{-- DELANTEROS (3) --}}
                        <div class="flex justify-center space-x-6 mt-6">
                            @foreach ($delanteros as $jugador)
                                @include('partials.jugador-campo-dashboard', ['jugador' => $jugador])
                            @endforeach
                            @for ($i = $delanteros->count(); $i < 3; $i++)
                                <div
                                    class="flex items-center justify-center w-14 h-14 bg-white rounded-full ml-3 mr-3 mt-6 p-1">
                                    <span class="text-red-600 font-bold text-2xl">-4</span>
                                </div>
                            @endfor
                        </div>

                        {{-- MEDIOCAMPOS (3) --}}
                        <div class="flex justify-center space-x-6 mt-6">
                            @foreach ($mediocampistas as $jugador)
                                @include('partials.jugador-campo-dashboard', ['jugador' => $jugador])
                            @endforeach
                            @for ($i = $mediocampistas->count(); $i < 3; $i++)
                                <div
                                    class="flex items-center justify-center w-14 h-14 bg-white rounded-full ml-3 mr-3 mt-6 p-1">
                                    <span class="text-red-600 font-bold text-2xl">-4</span>
                                </div>
                            @endfor
                        </div>

                        {{-- DEFENSAS (4) --}}
                        <div class="flex justify-center space-x-6 mt-6">
                            @foreach ($defensas as $jugador)
                                @include('partials.jugador-campo-dashboard', ['jugador' => $jugador])
                            @endforeach
                            @for ($i = $defensas->count(); $i < 4; $i++)
                                <div
                                    class="flex items-center justify-center w-14 h-14 bg-white rounded-full ml-3 mr-3 mt-6 p-1">
                                    <span class="text-red-600 font-bold text-2xl">-4</span>
                                </div>
                            @endfor
                        </div>

                        {{-- PORTERO (1) --}}
                        <div class="flex justify-center mt-6">
                            @foreach ($porteros as $jugador)
                                @include('partials.jugador-campo-dashboard', ['jugador' => $jugador])
                            @endforeach
                            @for ($i = $porteros->count(); $i < 1; $i++)
                                <div
                                    class="flex items-center justify-center w-14 h-14 bg-white rounded-full ml-3 mr-3 mt-6 p-1">
                                    <span class="text-red-600 font-bold text-2xl">-4</span>
                                </div>
                            @endfor
                        </div>

                    </div>
                </div>
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