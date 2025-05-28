<x-layouts.app :title="__('Mercado')">
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

    {{-- CONTENIDO DEL MERCADO --}}
    <div class="flex flex-col gap-4 px-4">
        @if (session('error'))
            <div class="bg-red-500 text-white px-4 py-2 rounded mb-4">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="bg-green-500 text-white px-4 py-2 rounded mb-4">{{ session('success') }}</div>
        @endif

        <div class="p-4 bg-gray-100 rounded-xl">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($players as $player)
                    @php
                        $estadisticas = $player->estadisticasTemporada;
                        $racha = is_array($estadisticas->racha_puntos ?? null)
                            ? $estadisticas->racha_puntos
                            : json_decode($estadisticas->racha_puntos ?? '[]', true);
                        $ultimosCinco = array_slice($racha, 0, 5);

                        switch ($player->posicion) {
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
                        data-jugador='@json($player)' data-estadisticas='@json($estadisticas)'>
                        <div class="flex items-center gap-3 jugador-info">
                            <img src="{{ $player->imagen }}" alt="{{ ucwords($player->nombre) }}"
                                class="w-14 h-14 rounded-full border border-gray-300">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2">
                                    <span class="text-base text-black font-semibold">{{ ucwords($player->nombre) }}</span>
                                    <img src="{{ $player->equipo->escudo }}" class="w-6 h-6"
                                        alt="{{ $player->equipo->nombre }}">
                                </div>
                                <span
                                    class="text-xs text-white px-2 py-0.5 rounded {{ $posColor }} inline-block w-fit whitespace-nowrap">
                                    {{ $player->posicion }}
                                </span>
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

                        <div class="mt-2 flex justify-end">
                            <form method="POST" action="{{ route('jugador.comprar', $player->id) }}" class="form-comprar">
                                @csrf
                                <button type="submit"
                                    class="bg-gray-200 border border-gray-400 rounded-lg text-black px-3 py-1 text-sm hover:bg-gray-300 transition cursor-pointer">Comprar</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- MODAL DE CONFIRMACIÓN DE COMPRA --}}
    <div id="confirm-modal" class="fixed inset-0 bg-black/80 hidden justify-center items-center z-50">
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

    {{-- MODAL DE INFORMACIÓN DEL JUGADOR --}}
    <div id="jugador-modal" class="fixed inset-0 bg-black/80 hidden justify-center items-center z-50">
        <div
            class="bg-white p-6 rounded-lg w-full max-w-md border border-gray-200 shadow-lg relative max-h-[90vh] overflow-hidden">
            <button onclick="cerrarJugadorModal()"
                class="absolute top-2 right-2 text-gray-500 hover:text-black text-2xl font-bold cursor-pointer">&times;</button>
            <div id="jugador-info-content" style="overflow-y: visible; max-height: none;"></div>
        </div>
    </div>

    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Scrollbar visible para el contenedor horizontal */
        .scrollbar-visible::-webkit-scrollbar {
            height: 12px;
        }

        .scrollbar-visible::-webkit-scrollbar-thumb {
            background-color: rgba(100, 100, 100, 0.6);
            border-radius: 10px;
        }

        .scrollbar-visible {
            scrollbar-width: thin;
            scrollbar-color: rgba(100, 100, 100, 0.6) transparent;
        }
    </style>


    {{-- SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // --- MODAL CONFIRMACIÓN DE COMPRA ---
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

            // --- MODAL INFORMACIÓN DEL JUGADOR ---
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
                                    No ha jugat ningún partit.
                                </div>`;

                        jugadorContent.innerHTML = `
                            <h3 class="text-2xl text-black font-bold mb-4 text-center">${jugador.nombre.split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ')}</h3>
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
                                <h4 class="font-semibold text-black text-base mb-2">📊 Historial de puntos:</h4>
                                <div>
                                ${puntosHTML}
                                </div>
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