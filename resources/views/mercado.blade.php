<x-layouts.app :title="__('Mercado')">
    {{-- ENCABEZADO USUARIO --}}
    <div
        class="bg-purple-700 text-black p-4 rounded-2xl shadow-lg flex flex-col sm:flex-row justify-between items-center border-2 border-purple-800 mb-6">
        <div class="text-center sm:text-left">
            <p class="text-sm">Usuario:</p>
            <p class="text-2xl font-bold">{{ $ligaUser->user->name ?? 'Sin nombre' }}</p>
            <p class="text-sm mt-1">{{ $ligaUser->user->puntos ?? '0' }} pts |
                {{ number_format($ligaUser->saldo, 2, ',', '.') }} €
            </p>
        </div>
        <div class="mt-4 sm:mt-0 text-center sm:text-right">
            <p class="text-sm">Valor de tu equipo:</p>
            <p class="text-2xl font-bold">65M€</p>
        </div>
    </div>

    {{-- CONTENIDO DEL MERCADO --}}
    <div class="flex flex-col gap-4 px-4">
        @if (session('error'))
            <div class="bg-red-500 text-white px-4 py-2 rounded mb-4">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="bg-green-500 text-white px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="p-4 bg-blue-900 rounded-xl">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($players as $player)
                    @php
                        $estadisticas = $player->estadisticasTemporada;
                        switch ($player->posicion) {
                            case 'Delantero':
                                $posColor = 'bg-red-600';
                                break;
                            case 'Centrocampista':
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
                        data-jugador='@json($player)' data-estadisticas='@json($estadisticas)'>
                        <div class="flex items-center gap-3 jugador-info">
                            <img src="{{ $player->imagen }}" alt="{{ $player->nombre }}"
                                class="w-14 h-14 rounded-full border border-gray-300">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2">
                                    <span class="text-base font-semibold">{{ $player->nombre }}</span>
                                    <img src="{{ $player->equipo->escudo }}" class="w-6 h-6"
                                        alt="{{ $player->equipo->nombre }}">
                                </div>
                                <span
                                    class="text-xs text-black px-2 py-0.5 rounded {{ $posColor }}">{{ $player->posicion }}</span>
                            </div>
                        </div>

                        <div class="text-sm text-gray-700 mt-2 jugador-info">
                            <div>{{ number_format($player->valor_actual, 0, ',', '.') }} €
                                @if ($player->diferencia > 0)
                                    <span class="text-green-600">▲
                                        {{ number_format($player->diferencia, 0, ',', '.') }} €</span>
                                @else
                                    <span class="text-red-600">▼
                                        {{ number_format(abs($player->diferencia), 0, ',', '.') }} €</span>
                                @endif
                            </div>
                            <div>Puntos totales: {{ $estadisticas->puntos_totales ?? 0 }}</div>
                        </div>

                        <div class="mt-2 flex justify-end">
                            <form method="POST" action="{{ route('jugador.comprar', $player->id) }}" class="form-comprar">
                                @csrf
                                <button type="submit"
                                    class="bg-gray-200 border border-gray-400 rounded-lg px-3 py-1 text-sm hover:bg-gray-300 transition">Comprar</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- MODAL DE COMPRA --}}
    <div id="confirm-modal" class="fixed inset-0 bg-black bg-opacity-60 hidden justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg w-11/12 max-w-md border border-gray-200 shadow-lg">
            <h3 class="text-lg font-bold mb-4">Confirmar compra</h3>
            <p class="text-gray-700 mb-6">¿Estás seguro de que quieres comprar este jugador?</p>
            <div class="flex justify-end gap-4">
                <button onclick="cerrarModal()"
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 cursor-pointer">Cancelar</button>
                <button id="confirm-btn"
                    class="bg-green-500 hover:bg-green-600 text-white border border-green-700 px-4 py-2 rounded cursor-pointer">Confirmar</button>
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

    {{-- SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const confirmModal = document.getElementById('confirm-modal');
            const confirmBtn = document.getElementById('confirm-btn');
            let activeForm = null;

            document.querySelectorAll('.form-comprar').forEach(form => {
                form.addEventListener('submit', e => {
                    e.preventDefault();
                    activeForm = form;
                    confirmModal.classList.remove('hidden');
                    confirmModal.classList.add('flex');
                });
            });

            window.cerrarModal = () => {
                confirmModal.classList.add('hidden');
                confirmModal.classList.remove('flex');
                activeForm = null;
            };

            confirmBtn.addEventListener('click', () => {
                if (activeForm) activeForm.submit();
            });

            const jugadorModal = document.getElementById('jugador-modal');
            const jugadorContent = document.getElementById('jugador-info-content');

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

            window.cerrarJugadorModal = () => {
                jugadorModal.classList.add('hidden');
                jugadorModal.classList.remove('flex');
            };
        });
    </script>
</x-layouts.app>