<x-layouts.app :title="__('Alineación')">
    <!-- Botones para el administrador -->
    @if (Auth::id() === $liga->administrador_id)
        <div class="flex justify-center gap-4 mb-6. mt-6">
            <form method="POST" action="{{ route('jornada.iniciar') }}">
                @csrf
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 cursor-pointer">
                    Iniciar Jornada
                </button>
            </form>
            <form method="POST" action="{{ route('jornada.finalizar') }}">
                @csrf
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 cursor-pointer">
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
                            class="flex flex-col items-center p-2 rounded-lg mr-2 mb-6 mt-6 p-1 cursor-pointer">
                            <img src="{{ $j->imagen }}" alt="{{ $j->nombre }}" class="w-16 h-16 rounded-full mb-1">
                            <span class="text-gray-800 font-semibold text-sm text-center">{{ $j->nombre }}</span>
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
                            class="flex flex-col items-center p-2 rounded-lg mr-2 mb-6 p-1 cursor-pointer">
                            <img src="{{ $j->imagen }}" alt="{{ $j->nombre }}" class="w-16 h-16 rounded-full mb-1">
                            <span class="text-gray-800 font-semibold text-sm text-center">{{ $j->nombre }}</span>
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
                            class="flex flex-col items-center p-2 rounded-lg mr-2 mb-6 p-1 cursor-pointer">
                            <img src="{{ $j->imagen }}" alt="{{ $j->nombre }}" class="w-16 h-16 rounded-full mb-1">
                            <span class="text-gray-800 font-semibold text-sm text-center">{{ $j->nombre }}</span>
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
                            class="flex flex-col items-center p-2 rounded-lg mr-2 mt-6 mb-6 p-1 cursor-pointer">
                            <img src="{{ $j->imagen }}" alt="{{ $j->nombre }}" class="w-16 h-16 rounded-full mb-1">
                            <span class="text-gray-800 font-semibold text-sm text-center">{{ $j->nombre }}</span>
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
        <div class="p-4 bg-blue-900 rounded-xl">
            <h2 class="text text-lg font-semibold mb-4">Suplentes</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach ($noActivos as $jugadorUserLiga)
                                @php
                                    $jugador = $jugadorUserLiga->jugador;
                                @endphp
                                <div class="bg-white rounded-xl p-4 shadow-md border border-gray-200">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $jugador->imagen }}" alt="{{ $jugador->nombre }}" class="w-14 h-14 rounded-full">
                                        <div>
                                            <div class="font-semibold">{{ $jugador->nombre }}</div>
                                            <div class="text-sm text-gray-600">Equipo:
                                                <img class="inline w-6 h-6" src="{{ $jugador->equipo->escudo }}"
                                                    alt="{{ $jugador->equipo->nombre }}">
                                            </div>
                                            <div class="text-sm text-gray-600">Posición: {{ $jugador->posicion }}</div>
                                            <div class="text-sm text-gray-600">Valor:
                                                {{ number_format($jugador->valor_actual, 0, ',', '.') }}
                                                ({{ number_format($jugador->diferencia, 0, ',', '.') }})
                                            </div>
                                            <div class="text-sm text-gray-900">Puntos:
                                                {{ $jugador->estadisticasTemporada->puntos_totales ?? 0 }}
                                            </div>
                                            <div class="text-sm text-gray-900">Últimos Puntos:
                                                {{ $jugador->estadisticasTemporada->racha_puntos ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                @endforeach
            </div>
        </div>

        <!-- Modal -->
        <!-- Modal -->
        <div id="modal" class="fixed inset-0 bg-black bg-opacity-60 hidden justify-center items-center z-50">
            <div class="bg-white p-6 rounded-lg w-11/12 max-w-md border border-gray-200 shadow-lg">
                <h3 class="text-lg font-bold mb-4">Selecciona un jugador para intercambiar</h3>
                <div class="grid grid-cols-2 gap-4 max-h-80 overflow-y-auto">

                    @foreach ($noActivos as $jugadorUserLiga)
                        @php $s = $jugadorUserLiga->jugador; @endphp

                        <form method="POST" action="{{ route('alineacion.intercambiar') }}" class="modal-item hidden"
                            data-pos="{{ $s->posicion }}">
                            @csrf
                            <input type="hidden" name="activo_id" value="">
                            <input type="hidden" name="suplente_id" value="{{ $s->id }}">

                            <button type="submit"
                                class="block w-full bg-gray-100 hover:bg-blue-100 transition rounded p-2 text-left border border-gray-200 cursor-pointer">
                                <div class="font-semibold">{{ $s->nombre }}</div>
                                <div class="text-sm text-gray-600">
                                    Equipo:
                                    <img class="inline w-6 h-6" src="{{ $s->equipo->escudo }}"
                                        alt="{{ $s->equipo->nombre }}">
                                </div>
                                <div class="text-sm text-gray-600">Posición: {{ $s->posicion }}</div>
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
    </script>
</x-layouts.app>