<x-layouts.app :title="__('Dashboard')">
    <div class="max-w-6xl mx-auto px-4 py-6">

        {{-- ENCABEZADO USUARIO --}}
        <div
            class="bg-blue-200 text-black p-4 rounded-2xl shadow-lg flex flex-col sm:flex-row justify-between items-center mb-6">
            <div class="text-center sm:text-left">
                <p class="text-2xl font-bold">{{ $ligaUser->user->name ?? 'Sin nombre' }}</p>
                <p class="text-2xl mt-1"><strong>Puntos totales: </strong>{{ $ligaUser->puntos_totales ?? '0' }}</p>
                <p class="text-2xl mt-1"><strong>Saldo: </strong>
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

        {{-- CÓDIGO DE LIGA CON BOTÓN DE COPIA --}}
        <div class="flex justify-center mb-6">
            <div class="bg-purple-100 px-6 py-4 rounded-xl shadow text-center">
                <p class="text-xl font-semibold text-purple-800 mb-2">Comparte el código con tus amigos:</p>
                <div class="flex items-center justify-center gap-2">
                    <span id="liga-codigo" class="text-3xl font-bold text-purple-900">{{ $ligaId }}</span>
                    <button onclick="copiarLigaCodigo()" title="Copiar al portapapeles"
                        class="text-purple-700 hover:text-purple-900 p-2 rounded-full transition duration-200 hover:bg-purple-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2M16 8h2a2 2 0 012 2v8a2 2 0 01-2 2h-8a2 2 0 01-2-2v-2" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div id="toast-copiado"
            class="fixed bottom-6 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-4 py-2 rounded shadow-lg text-sm hidden z-50">
            ¡Código copiado al portapapeles!
        </div>

        <script>
            function copiarLigaCodigo() {
                const codigo = document.getElementById('liga-codigo').textContent;
                navigator.clipboard.writeText(codigo)
                    .then(() => {
                        const toast = document.getElementById('toast-copiado');
                        toast.classList.remove('hidden');
                        setTimeout(() => toast.classList.add('hidden'), 3000);
                    });
            }
        </script>

        {{-- Selector de jornada mejorado --}}
        <form method="GET" action="{{ route('dashboard') }}" class="mb-6 text-center">
            <input type="hidden" name="liga_id" value="{{ $ligaId }}">
            <div class="flex flex-wrap justify-center gap-2">
                @foreach ($jornadas as $jornada)
                    <button type="submit" name="jornada_id" value="{{ $jornada }}"
                        class="px-4 py-1 rounded-full text-sm font-semibold border
                                                                    {{ $jornada == $jornadaSeleccionada ? 'bg-purple-700 text-white' : 'bg-white text-purple-700 border-purple-300 hover:bg-purple-100' }}">
                        Jornada {{ $jornada }}
                    </button>
                @endforeach
            </div>
        </form>

        {{-- Contenido principal: clasificación y campo --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- CLASIFICACIÓN --}}
            <div class="bg-white dark:bg-neutral-800 p-4 rounded-xl shadow border border-purple-300">
                <h2 class="font-bold text-center text-white bg-purple-700 p-2 rounded-t">
                    {{ strtoupper('CLASIFICACIÓN') }} @isset($ligaNombre)
                        - {{ strtoupper($ligaNombre) }}
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
                                <th class="px-2 py-1 w-16 text-center">Puntos</th>
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
                @if (empty($jornadas) || count($jornadas) === 0)
                    <div
                        class="bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-6 rounded-lg text-center font-semibold mb-4">
                        Todavía no has jugado ninguna jornada. Espera a que se inicie una.
                    </div>
                @else
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

                            {{-- DELANTEROS --}}
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

                            {{-- MEDIOCAMPOS --}}
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

                            {{-- DEFENSAS --}}
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

                            {{-- PORTERO --}}
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
                @endif
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

php artisan serve
http://localhost:8000/dashboard
http://localhost:8000/scrape-players
-->