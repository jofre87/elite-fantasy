<x-layouts.app :title="__('Alineación')">
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


    <h2 class="text-lg font-semibold mb-6 text-center">Once titular (4-3-3)</h2>
    <div class="flex h-full w-full flex-1 flex-col gap-4 items-center justify-center">
        <!-- Activos -->
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

            <div class="flex flex-col h-full mt-6">
                {{-- Delanteros: 3 plazas --}}
                <div class="flex justify-center space-x-8 mt-6">
                    @foreach($delanteros as $u)
                        @php $j = $u->jugador; @endphp
                        <button onclick="seleccionarJugador({{ $j->id }}, '{{ $j->posicion }}')"
                            class="flex flex-col items-center p-2 rounded-lg mr-2 mb-6 mt-6 cursor-pointer">
                            <img src="{{ $j->imagen }}" alt="{{ $j->nombre }}" class="w-16 h-16 rounded-full mb-1">
                            <span class="text-gray-800 font-semibold text-sm text-center">{{ ucwords($j->nombre) }}</span>
                        </button>
                    @endforeach
                    {{-- Huecos vacíos --}}
                    @for($i = $delanteros->count(); $i < 3; $i++)
                        <button onclick="seleccionarJugador(null, 'Delantero')"
                            class="flex items-center justify-center w-16 h-16 bg-white rounded-lg mr-2 mt-6 mb-6 p-1 cursor-pointer">
                            <span class="text-red-600 font-bold text-2xl">-4</span>
                        </button>
                    @endfor
                </div>

                {{-- Mediocampistas: 3 plazas --}}
                <div class="flex justify-center space-x-8">
                    @foreach($mediocampistas as $u)
                        @php $j = $u->jugador; @endphp
                        <button onclick="seleccionarJugador({{ $j->id }}, '{{ $j->posicion }}')"
                            class="flex flex-col items-center p-2 rounded-lg mr-2 mb-6 cursor-pointer">
                            <img src="{{ $j->imagen }}" alt="{{ $j->nombre }}" class="w-16 h-16 rounded-full mb-1">
                            <span class="text-gray-800 font-semibold text-sm text-center">{{ ucwords($j->nombre) }}</span>
                        </button>
                    @endforeach
                    @for($i = $mediocampistas->count(); $i < 3; $i++)
                        <button onclick="seleccionarJugador(null, 'Mediocampista')"
                            class="flex items-center justify-center w-16 h-16 bg-white rounded-lg mr-2 mt-6 mb-6 p-1 cursor-pointer">
                            <span class="text-red-600 font-bold text-2xl">-4</span>
                        </button>
                    @endfor
                </div>

                {{-- Defensas: 4 plazas --}}
                <div class="flex justify-center space-x-6">
                    @foreach($defensas as $u)
                        @php $j = $u->jugador; @endphp
                        <button onclick="seleccionarJugador({{ $j->id }}, '{{ $j->posicion }}')"
                            class="flex flex-col items-center p-2 rounded-lg mr-2 mb-6 cursor-pointer">
                            <img src="{{ $j->imagen }}" alt="{{ $j->nombre }}" class="w-16 h-16 rounded-full mb-1">
                            <span class="text-gray-800 font-semibold text-sm text-center">{{ ucwords($j->nombre) }}</span>
                        </button>
                    @endforeach
                    @for($i = $defensas->count(); $i < 4; $i++)
                        <button onclick="seleccionarJugador(null , 'Defensa')"
                            class="flex items-center justify-center w-16 h-16 bg-white rounded-lg mr-2 mt-6 mb-6 p-1 cursor-pointer">
                            <span class="text-red-600 font-bold text-2xl">-4</span>
                        </button>
                    @endfor
                </div>

                {{-- Portero: 1 plaza --}}
                <div class="flex justify-center">
                    @foreach($porteros as $u)
                        @php $j = $u->jugador; @endphp
                        <button onclick="seleccionarJugador({{ $j->id }}, '{{ $j->posicion }}')"
                            class="flex flex-col items-center p-2 rounded-lg mr-2 mt-6 mb-6 cursor-pointer">
                            <img src="{{ $j->imagen }}" alt="{{ $j->nombre }}" class="w-16 h-16 rounded-full mb-1">
                            <span class="text-gray-800 font-semibold text-sm text-center">{{ ucwords($j->nombre) }}</span>
                        </button>
                    @endforeach
                    @for($i = $porteros->count(); $i < 1; $i++)
                        <button onclick="seleccionarJugador(null, 'Portero')"
                            class="flex items-center justify-center w-16 h-16 bg-white rounded-lg mr-2 mt-6 mb-0 p-1 cursor-pointer">
                            <span class="text-red-600 font-bold text-2xl">-4</span>
                        </button>
                    @endfor
                </div>
            </div>
        </div>



        <!-- Suplentes -->
        <div class="p-4 bg-blue-700 rounded-xl">
            <h2 class="text-white text-lg font-semibold mb-4 text-center">Suplentes</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($noActivos as $jugadorUserLiga)
                    @php
                        $jugador = $jugadorUserLiga->jugador;
                        $estadisticas = $jugador->estadisticasTemporada;
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

                    <div class="bg-white rounded-xl p-3 shadow-md border border-gray-200 cursor-pointer grupo-jugador"
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
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Modal Cambiar jugador -->
        <div id="modal" class="fixed inset-0 bg-opacity-60 hidden justify-center items-center z-50">
            <div class="bg-white p-6 rounded-lg w-11/12 max-w-md border border-gray-200 shadow-lg">
                <h3 class="text-lg font-bold mb-4">Selecciona un jugador para intercambiar</h3>
                <div class="grid grid-cols-1 gap-4 max-h-80 overflow-y-auto">

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

                </div>
                <div class="mt-4 text-right">
                    <button onclick="cerrarModal()"
                        class="bg-red-500 px-4 py-2 rounded hover:bg-red-600 cursor-pointer">Cancelar</button>
                </div>
            </div>
        </div>

        <!-- MODAL DE INFORMACIÓN DEL JUGADOR -->
        <div id="jugador-modal"
            class="fixed inset-0 bg-black bg-opacity-60 hidden justify-center items-center z-50 overflow-y-auto">
            <div class="bg-white p-6 rounded-lg w-full max-w-md border border-gray-200 shadow-lg relative">
                <button onclick="cerrarJugadorModal()"
                    class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl font-bold">&times;</button>
                <div id="jugador-info-content" class="overflow-y-auto max-h-[80vh]"></div>
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

        function cerrarModal() {
            document.getElementById('modal').classList.remove('flex');
            document.getElementById('modal').classList.add('hidden');
        }

        document.querySelectorAll('.grupo-jugador').forEach(div => {
            div.querySelectorAll('.jugador-info').forEach(infoDiv => {
                infoDiv.addEventListener('click', () => {
                    const jugador = JSON.parse(div.dataset.jugador);
                    const estadisticas = JSON.parse(div.dataset.estadisticas || '{}');
                    const puntosJornada = estadisticas.puntos_por_jornada ?? [];

                    const listaPuntos = puntosJornada.length ?
                        '<ul class="list-disc pl-5 text-sm text-gray-700 mt-2">' +
                        puntosJornada.map((p, i) =>
                            `<li>Jornada ${i + 1}: ${p} pts</li>`).join('') + '</ul>' :
                        '<p class="text-sm text-gray-500">Sin estadísticas de jornadas.</p>';

                    jugadorContent.innerHTML = `
                            <h3 class="text-2xl font-bold mb-4 text-center">${jugador.nombre}</h3>
                            <div class="flex gap-6 items-center mb-6">
                                <img src="${jugador.imagen}" alt="${jugador.nombre}" class="w-28 h-28 rounded-full border border-gray-300">
                                <div>
                                    <p class="text-sm text-gray-700"><strong>Equipo:</strong> ${jugador.equipo?.nombre ?? 'Sin equipo'} <img src="${jugador.equipo?.escudo}" class="inline w-5 h-5 ml-1"></p>
                                    <p class="text-sm text-gray-700"><strong>Posición:</strong> ${jugador.posicion}</p>
                                    <p class="text-sm text-gray-700"><strong>Valor actual:</strong> ${Number(jugador.valor_actual).toLocaleString('es-ES')} €</p>
                                    <p class="text-sm text-gray-700"><strong>Diferencia:</strong> ${jugador.diferencia > 0 ? '▲' : '▼'} ${Math.abs(jugador.diferencia).toLocaleString('es-ES')} €</p>
                                    <p class="text-sm text-gray-700"><strong>Puntos totales:</strong> ${estadisticas.puntos_totales ?? 0}</p>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-semibold text-base mb-2">📊 Puntos por jornada:</h4>
                                ${listaPuntos}
                            </div>
                        `;

                    jugadorModal.classList.remove('hidden');
                    jugadorModal.classList.add('flex');
                });
            });
        });

    </script>
</x-layouts.app>