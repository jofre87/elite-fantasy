<x-layouts.app :title="__('Alineación')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">

        @if (session('success'))
            <div class="bg-green-500 text-white px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Once titular -->
        <div class="p-4 bg-blue-900 rounded-xl">
            <h2 class="text-white text-lg font-semibold mb-4">Once titular</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                @foreach ($activos as $jugadorUserLiga)
                    @php
                        $jugador = $jugadorUserLiga->jugador;
                    @endphp
                    <div onclick="seleccionarJugador({{ $jugador->id }})"
                        class="cursor-pointer bg-white rounded-xl p-4 shadow-md border border-gray-200">
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

        <!-- Suplentes -->
        <div class="p-4 bg-blue-900 rounded-xl">
            <h2 class="text-white text-lg font-semibold mb-4">Suplentes</h2>
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
        <div id="modal" class="fixed inset-0 bg-black bg-opacity-60 hidden justify-center items-center z-50">
            <div class="bg-white p-6 rounded-lg w-11/12 max-w-md">
                <h3 class="text-lg font-bold mb-4">Selecciona un jugador para intercambiar</h3>
                <div class="grid grid-cols-2 gap-4 max-h-80 overflow-y-auto">
                    @foreach ($noActivos as $jugadorUserLiga)
                        @php
                            $jugador = $jugadorUserLiga->jugador;
                        @endphp
                        <form method="POST" action="{{ route('alineacion.intercambiar') }}">
                            @csrf
                            <input type="hidden" name="activo_id" id="activo_id">
                            <input type="hidden" name="suplente_id" value="{{ $jugador->id }}">
                            <button type="submit"
                                class="block w-full bg-gray-100 hover:bg-blue-100 transition rounded p-2 text-left">
                                <div class="font-semibold">{{ $jugador->nombre }}</div>
                                <div class="text-sm text-gray-600">Equipo:
                                    <img class="inline w-6 h-6" src="{{ $jugador->equipo->escudo }}"
                                        alt="{{ $jugador->equipo->nombre }}">
                                </div>
                                <div class="text-sm text-gray-600">Posición: {{ $jugador->posicion }}</div>
                            </button>
                        </form>
                    @endforeach
                </div>
                <div class="mt-4 text-right">
                    <button onclick="cerrarModal()"
                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function seleccionarJugador(jugadorId) {
            document.getElementById('activo_id').value = jugadorId;
            document.getElementById('modal').classList.remove('hidden');
            document.getElementById('modal').classList.add('flex');
        }

        function cerrarModal() {
            document.getElementById('modal').classList.remove('flex');
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
</x-layouts.app>