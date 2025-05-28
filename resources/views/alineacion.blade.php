<x-layouts.app :title="__('ELITEFANTASY')">
    @if (session('error'))
        <div class="bg-red-500 text-white px-4 py-2 rounded mb-4">{{ session('error') }}</div>
    @endif
    @if (session('success'))
        <div class="bg-green-500 text-white px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    <!-- Botones para el administrador -->
    @if (Auth::id() === $liga->administrador_id)
        @php
            $iniciarDisabled = $liga->jornada_activa;
            $finalizarDisabled = !$liga->jornada_activa;

            $iniciarClase = $iniciarDisabled
                ? 'bg-gray-400 cursor-not-allowed'
                : 'bg-green-500 text-white hover:bg-green-600 cursor-pointer';

            $finalizarClase = $finalizarDisabled
                ? 'bg-gray-400 cursor-not-allowed'
                : 'bg-red-500 text-white hover:bg-red-600 cursor-pointer';
        @endphp

        <div class="flex justify-center gap-4 mb-6 mt-6">
            <form method="POST" action="{{ route('jornada.iniciar') }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded {{ $iniciarClase }}" {{ $iniciarDisabled ? 'disabled' : '' }}>
                    Iniciar Jornada
                </button>
            </form>

            <form method="POST" action="{{ route('jornada.finalizar') }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded {{ $finalizarClase }}" {{ $finalizarDisabled ? 'disabled' : '' }}>
                    Finalizar Jornada
                </button>
            </form>
        </div>
    @endif

    <div class="flex flex-wrap gap-4 items-center justify-center mb-4 sm:flex-row">
        <div class="min-w-[280px]">
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
        </div>
        <!-- Activos -->
        <div>
            <h2 class="text-lg font-semibold mb-4 text-center">Once titular (4-3-3)</h2>
            <div class="rounded-xl bg-cover bg-center" style="
                background-image: url('{{ asset('img/campo.jpg') }}');
                width: 419px;
                height: 612px;
            ">
                @php
                    // Filtramos por posición
                    $porteros = $activos->filter(fn($u) => $u->jugador->posicion === 'Portero');
                    $defensas = $activos->filter(fn($u) => $u->jugador->posicion === 'Defensa');
                    $mediocampistas = $activos->filter(fn($u) => $u->jugador->posicion === 'Mediocampista');
                    $delanteros = $activos->filter(fn($u) => $u->jugador->posicion === 'Delantero');
                @endphp

                <div class="flex flex-col h-full">
                    {{-- Delanteros: 3 plazas --}}
                    <div class="flex justify-center space-x-8 mt-6">
                        @foreach ($delanteros as $u)
                            @php $j = $u->jugador; @endphp
                            <button onclick="seleccionarJugador({{ $j->id }}, '{{ $j->posicion }}')"
                                class="flex flex-col items-center p-2 rounded-lg ml-0 mr-0 mt-10 cursor-pointer">
                                <img src="{{ $j->imagen }}" alt="{{ $j->nombre }}" class="w-16 h-16 rounded-full mb-1">
                                <span
                                    class="text-gray-800 font-semibold text-sm text-center">{{ ucwords($j->nombre) }}</span>
                            </button>
                        @endforeach
                        {{-- Huecos vacíos --}}
                        @for ($i = $delanteros->count(); $i < 3; $i++)
                            <button onclick="seleccionarJugador(null, 'Delantero')"
                                class="flex items-center justify-center w-16 h-16 bg-white rounded-full ml-3 mr-3 mt-15 p-1 cursor-pointer">
                                <span class="text-red-600 font-bold text-2xl">-4</span>
                            </button>
                        @endfor
                    </div>

                    {{-- Mediocampistas: 3 plazas --}}
                    <div class="flex justify-center space-x-8">
                        @foreach ($mediocampistas as $u)
                            @php $j = $u->jugador; @endphp
                            <button onclick="seleccionarJugador({{ $j->id }}, '{{ $j->posicion }}')"
                                class="flex flex-col items-center p-2 rounded-lg ml-0 mr-0 mt-10 cursor-pointer">
                                <img src="{{ $j->imagen }}" alt="{{ $j->nombre }}" class="w-16 h-16 rounded-full mb-1">
                                <span
                                    class="text-gray-800 font-semibold text-sm text-center">{{ ucwords($j->nombre) }}</span>
                            </button>
                        @endforeach
                        @for ($i = $mediocampistas->count(); $i < 3; $i++)
                            <button onclick="seleccionarJugador(null, 'Mediocampista')"
                                class="flex items-center justify-center w-16 h-16 bg-white rounded-full ml-3 mr-3 mt-15 p-1 cursor-pointer">
                                <span class="text-red-600 font-bold text-2xl">-4</span>
                            </button>
                        @endfor
                    </div>

                    {{-- Defensas: 4 plazas --}}
                    <div class="flex justify-center space-x-6">
                        @foreach ($defensas as $u)
                            @php $j = $u->jugador; @endphp
                            <button onclick="seleccionarJugador({{ $j->id }}, '{{ $j->posicion }}')"
                                class="flex flex-col items-center p-2 rounded-lg ml-0 mr-0 mt-10 cursor-pointer">
                                <img src="{{ $j->imagen }}" alt="{{ $j->nombre }}" class="w-16 h-16 rounded-full mb-1">
                                <span
                                    class="text-gray-800 font-semibold text-sm text-center">{{ ucwords($j->nombre) }}</span>
                            </button>
                        @endforeach
                        @for ($i = $defensas->count(); $i < 4; $i++)
                            <button onclick="seleccionarJugador(null , 'Defensa')"
                                class="flex items-center justify-center w-16 h-16 bg-white rounded-full ml-3 mr-3 mt-15 p-1 cursor-pointer">
                                <span class="text-red-600 font-bold text-2xl">-4</span>
                            </button>
                        @endfor
                    </div>

                    {{-- Portero: 1 plaza --}}
                    <div class="flex justify-center">
                        @foreach ($porteros as $u)
                            @php $j = $u->jugador; @endphp
                            <button onclick="seleccionarJugador({{ $j->id }}, '{{ $j->posicion }}')"
                                class="flex flex-col items-center p-2 rounded-lg ml-2 mr-2 mt-5 cursor-pointer">
                                <img src="{{ $j->imagen }}" alt="{{ $j->nombre }}" class="w-16 h-16 rounded-full mb-1">
                                <span
                                    class="text-gray-800 font-semibold text-sm text-center">{{ ucwords($j->nombre) }}</span>
                            </button>
                        @endforeach
                        @for ($i = $porteros->count(); $i < 1; $i++)
                            <button onclick="seleccionarJugador(null, 'Portero')"
                                class="flex items-center justify-center w-16 h-16 bg-white rounded-full ml-3 mr-3 mt-12 p-1 cursor-pointer">
                                <span class="text-red-600 font-bold text-2xl">-4</span>
                            </button>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Suplentes -->
    <div class="p-4 bg-gray-100 rounded-xl">
        <h2 class="text-lg font-semibold mb-4 text-center">Suplentes</h2>
        @if ($noActivos->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($noActivos as $jugadorUserLiga)
                    @php
                        $jugador = $jugadorUserLiga->jugador;
                        $estadisticas = $jugador->estadisticasTemporada;
                        $racha = is_array($estadisticas->racha_puntos ?? null)
                            ? $estadisticas->racha_puntos
                            : json_decode($estadisticas->racha_puntos ?? '[]', true);
                        $ultimosCinco = array_slice($racha, 0, 5);

                        switch ($jugador->posicion) {
                            case 'Delantero':
                                $posColor = 'bg-red-600';
                                break;
                            case 'Mediocampista':
                                $posColor = 'bg-green-600';
                                break;
                            case 'Defensa':
                                $posColor = 'bg-blue-600';
                                break;
                            default:
                                $posColor = 'bg-gray-600';
                                break;
                        }
                    @endphp

                    <div class="bg-white rounded-xl p-3 shadow-md border border-gray-200 cursor-pointer grupo-jugador hover:border-blue-500 transition"
                        data-jugador='@json($jugador)' data-estadisticas='@json($estadisticas)'>
                        <div class="flex items-center gap-3 jugador-info">
                            <img src="{{ $jugador->imagen }}" alt="{{ $jugador->nombre }}"
                                class="w-14 h-14 rounded-full border border-gray-300">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2">
                                    <span class="text-base font-semibold">{{ ucwords($jugador->nombre) }}</span>
                                    <img src="{{ $jugador->equipo->escudo }}" class="w-6 h-6"
                                        alt="{{ $jugador->equipo->nombre }}">
                                </div>
                                <span
                                    class="text-xs text-white px-2 py-0.5 rounded {{ $posColor }} inline-block w-fit whitespace-nowrap">
                                    {{ $jugador->posicion }}
                                </span>
                            </div>
                        </div>

                        <div class="text-sm text-gray-700 mt-2 jugador-info">
                            <div>{{ number_format($jugador->valor_actual, 0, ',', '.') }} €
                                @if ($jugador->diferencia > 0)
                                    <span class="text-green-600">▲
                                        {{ number_format($jugador->diferencia, 0, ',', '.') }} €</span>
                                @else
                                    <span class="text-red-600">▼
                                        {{ number_format(abs($jugador->diferencia), 0, ',', '.') }} €</span>
                                @endif
                            </div>
                            <div>Puntos totales: {{ $estadisticas->puntos_totales ?? 0 }}</div>

                            {{-- Últimos 5 puntos con colores --}}
                            <div class="flex gap-1 mt-2">
                                @foreach (range(0, 4) as $i)
                                    @php
                                        $p = $ultimosCinco[$i] ?? null; // Si no existe, será null
                                        $color = 'bg-gray-400';
                                        if (is_numeric($p)) {
                                            if ($p < 0) {
                                                $color = 'bg-red-500';
                                            } elseif ($p >= 2 && $p <= 9) {
                                                $color = 'bg-green-500';
                                            } elseif ($p > 9) {
                                                $color = 'bg-blue-500';
                                            }
                                        }
                                        $jornada = count($racha) - count($ultimosCinco) + $i + 1;
                                    @endphp
                                    <span title="Jornada {{ $jornada }}"
                                        class="w-6 h-6 rounded-full text-white text-xs flex items-center justify-center {{ $color }}">
                                        {{ is_numeric($p) ? $p : '-' }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
        @else
                <div class="col-span-full text-center text-gray-600 text-lg font-medium">
                    No tienes suplentes disponibles.
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Cambiar jugador -->
    <div id="modal" class="fixed inset-0 bg-black/80 hidden justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg w-11/12 max-w-md border border-gray-200 shadow-lg">
            <h3 class="text-black text-lg font-bold mb-4">Desalinea o intercambia por un suplente</h3>
            <div class="grid grid-cols-1 gap-4 max-h-80 overflow-y-auto">
                @if ($noActivos->isEmpty())
                    <div class="text-center text-gray-600 text-sm font-medium col-span-full">
                        No tienes suplentes.
                    </div>
                @else
                    @foreach ($noActivos as $jugadorUserLiga)
                        @php $s = $jugadorUserLiga->jugador; @endphp

                        <form method="POST" action="{{ route('alineacion.intercambiar') }}" class="modal-item hidden"
                            data-pos="{{ $s->posicion }}">
                            @csrf
                            <input type="hidden" name="activo_id" value="">
                            <input type="hidden" name="suplente_id" value="{{ $s->id }}">

                            <button type="submit"
                                class="block w-full bg-gray-100 hover:bg-blue-100 transition rounded p-2 text-left border border-gray-200 cursor-pointer">
                                <div class="flex items-center gap-3">
                                    <div class="text-sm text-gray-600">
                                        <img class="inline w-6 h-6" src="{{ $s->equipo->escudo }}"
                                            alt="{{ $s->equipo->nombre }}">
                                    </div>
                                    <img src="{{ $s->imagen }}" alt="{{ $s->nombre }}"
                                        class="w-14 h-14 rounded-full border border-gray-300">
                                    <div class="flex flex-col">
                                        <div class="text-sm text-black">{{ ucwords($s->nombre) }}</div>
                                        <div class="text-sm text-gray-600">{{ $s->posicion }}</div>
                                    </div>
                                </div>
                            </button>
                        </form>
                    @endforeach
                @endif
            </div>

            <div class="flex justify-end gap-4">
                <!-- Botón para desalinear jugador -->
                <div class="mt-4">
                    <form method="POST" action="{{ route('alineacion.desalinear') }}">
                        @csrf
                        <input type="hidden" name="activo_id" id="activo-id-desalinear" value="">
                        <button type="submit"
                            class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 cursor-pointer">
                            Desalinear Jugador
                        </button>
                    </form>
                </div>
                <div class="mt-4">
                    <button onclick="cerrarModal()"
                        class="bg-red-500 px-4 py-2 rounded hover:bg-red-600 cursor-pointer">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL DE INFORMACIÓN DEL JUGADOR -->
    <div id="jugador-modal" class="fixed inset-0 bg-black/80 hidden justify-center items-center z-50">
        <div
            class="bg-white p-6 rounded-lg w-full max-w-md border border-gray-200 shadow-lg relative max-h-[90vh] overflow-hidden">
            <button onclick="cerrarModal()"
                class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl font-bold cursor-pointer">&times;</button>
            <div id="jugador-info-content" style="overflow-y: visible; max-height: none;"></div>
            <div class="mt-2 text-center">
                <button onclick="abrirModalVenta()"
                    class="bg-red-500 px-4 py-2 rounded hover:bg-red-600 cursor-pointer text-white">Vender
                    Jugador</button>
            </div>
        </div>
    </div>

    <!-- MODAL DE VENTA DEL JUGADOR -->
    <div id="venta-modal" class="fixed inset-0 bg-black/80 hidden justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg w-11/12 max-w-md border border-gray-200 shadow-lg">
            <h3 class="text-lg font-bold mb-4">Confirmar venta</h3>
            <p class="text-gray-700 mb-6">¿Estás seguro de que quieres vender este jugador?</p>
            <div class="flex justify-end gap-4">
                <button onclick="cerrarModalVenta()"
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 cursor-pointer">Cancelar</button>
                <form method="POST" action="{{ route('jugador.vender') }}">
                    @csrf
                    <input type="hidden" name="jugador_id" id="jugador-id-venta" value="">
                    <button id="confirm-btn" formaction="{{ route('jugador.vender') }}"
                        class="bg-green-500 hover:bg-green-600 text-white border border-green-700 px-4 py-2 rounded cursor-pointer">Confirmar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function seleccionarJugador(jugadorId, posicion) {
            // 1) Rellenamos todos los hidden de activo_id
            document.querySelectorAll('input[name="activo_id"]').forEach(input => {
                input.value = jugadorId;
            });

            // 2) Filtramos los items del modal por posición
            document.querySelectorAll('.modal-item').forEach(item => {
                if (item.dataset.pos === posicion) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });

            // 3) Abrimos el modal
            const modal = document.getElementById('modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        const jugadorModal = document.getElementById('jugador-modal');
        const jugadorContent = document.getElementById('jugador-info-content');

        document.querySelectorAll('.grupo-jugador').forEach(div => {
            div.querySelectorAll('.jugador-info').forEach(infoDiv => {
                infoDiv.addEventListener('click', () => {
                    const jugador = JSON.parse(div.dataset.jugador);
                    const estadisticas = JSON.parse(div.dataset.estadisticas || '{}');

                    let racha = Array.isArray(estadisticas.racha_puntos) ?
                        estadisticas.racha_puntos :
                        JSON.parse(estadisticas.racha_puntos || '[]');

                    // Limitar a máximo 38 jornadas
                    racha = racha.slice(0, 38);

                    const puntosScroll = racha.map((p, i) => {
                        let color = 'bg-gray-400';
                        if (p < 0) color = 'bg-red-500';
                        else if (p >= 2 && p <= 9) color = 'bg-green-500';
                        else if (p > 9) color = 'bg-blue-500';

                        const jornada = i + 1;

                        return `<div title="Jornada ${jornada}" class="w-12 h-12 flex-shrink-0 rounded-full text-white text-base font-semibold flex items-center justify-center ${color}">${p}</div>`;
                    }).join('');

                    const puntosHTML = puntosScroll.trim() ?
                        `
                                <div id="scroll-puntos" class="scrollbar-visible flex gap-3 overflow-x-auto scroll-touch p-3 bg-gray-100 rounded-lg max-w-full" 
                                    style="width: 100%; height: 90px; overflow-y: hidden;">
                                    ${puntosScroll}
                                </div>` :
                        `
                                <div class="p-3 text-gray-500 text-sm italic">
                                    No ha jugado ningún partido.
                                </div>`;

                    jugadorContent.innerHTML = `
                            <h3 class="text-2xl font-bold mb-4 text-center">${jugador.nombre.split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')}</h3>
                            <div class="flex gap-6 items-center mb-6">
                                <img src="${jugador.imagen}" alt="${jugador.nombre.split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')}" class="w-28 h-28 rounded-full border border-gray-300">
                                <div>
                                    <p class="text-sm text-gray-700"><strong>Equipo:</strong> ${jugador.equipo?.nombre ?? 'Sin equipo'} <img src="${jugador.equipo?.escudo}" class="inline w-5 h-5 ml-1"></p>
                                    <p class="text-sm text-gray-700"><strong>Posición:</strong> ${jugador.posicion}</p>
                                    <p class="text-sm text-gray-700"><strong>Valor actual:</strong> ${Number(jugador.valor_actual).toLocaleString('es-ES')} €</p>
                                    <p class="text-sm text-gray-700"><strong>Diferencia:</strong> ${jugador.diferencia > 0 ? '▲' : '▼'} ${Math.abs(jugador.diferencia).toLocaleString('es-ES')} €</p>
                                    <p class="text-sm text-gray-700"><strong>Puntos totales:</strong> ${estadisticas.puntos_totales ?? 0}</p>
                                </div>
                            </div>
                            <div class="p-6">
                                <h4 class="font-semibold text-base mb-2">📊 Historial de puntos:</h4>
                                <div class="pb-4">
                                ${puntosHTML}
                                </div>
                            </div>
                            <div data-jugador-id="${jugador.id}"></div>
                        `;

                    jugadorModal.classList.remove('hidden');
                    jugadorModal.classList.add('flex');
                });
            });
        });

        // Cerrar modal info
        function cerrarModal() {
            document.getElementById('modal').classList.remove('flex');
            document.getElementById('modal').classList.add('hidden');

            document.getElementById('jugador-modal').classList.remove('flex');
            document.getElementById('jugador-modal').classList.add('hidden');
        }

        function abrirModalVenta() {
            const ventaModal = document.getElementById('venta-modal');
            const jugadorIdDiv = document.querySelector('#jugador-info-content > div[data-jugador-id]');
            const jugadorId = jugadorIdDiv ? jugadorIdDiv.dataset.jugadorId : null;

            document.getElementById('jugador-id-venta').value = jugadorId || '';

            ventaModal.classList.remove('hidden');
            ventaModal.classList.add('flex');
        }

        function cerrarModalVenta() {
            const ventaModal = document.getElementById('venta-modal');
            ventaModal.classList.remove('flex');
            ventaModal.classList.add('hidden');
        }
    </script>
</x-layouts.app>