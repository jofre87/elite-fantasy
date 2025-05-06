<x-layouts.app :title="__('Mercado')">
    {{-- ——— CONTENIDO DE MERCADO ——— --}}
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <!-- Filtros -->
        <div class="flex justify-center gap-4 bg-blue-900 p-4 rounded-xl">
            <button class="bg-gray-200 text-black px-4 py-2 rounded-md hover:bg-blue-200 transition">Mercado</button>
            <button class="bg-gray-200 text-black px-4 py-2 rounded-md hover:bg-blue-200 transition">Ventas</button>
            <button class="bg-gray-200 text-black px-4 py-2 rounded-md hover:bg-blue-200 transition">Ofertas</button>
            <button class="bg-gray-200 text-black px-4 py-2 rounded-md hover:bg-blue-200 transition">Pujas</button>
        </div>

        <!-- Mensajes de sesión -->
        @if (session('error'))
            <div class="bg-red-500 text-white px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-500 text-white px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Grid de jugadores -->
        <div class="p-4 bg-blue-900 rounded-xl flex-1 overflow-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($players as $player)
                    @php
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

                    <div class="bg-white rounded-xl p-4 shadow-md flex flex-col gap-3 border border-gray-200">
                        <!-- Cabecera: imagen, nombre, escudo, posición -->
                        <div class="flex items-center gap-4">
                            <img src="{{ $player->imagen }}" alt="{{ $player->nombre }}"
                                class="w-14 h-14 rounded-full border border-gray-300">
                            <div class="flex flex-col">
                                <div class="flex items-center gap-2">
                                    <span class="text-base font-semibold">{{ $player->nombre }}</span>
                                    <img src="{{ $player->equipo->escudo }}" class="w-5 h-5"
                                        alt="{{ $player->equipo->nombre }}">
                                </div>
                                <span
                                    class="text-xs text-black px-2 py-0.5 rounded-full w-max mt-1 {{ $posColor }}">
                                    {{ $player->posicion }}
                                    <div>{{ $player->estadisticasTemporada->puntos_totales ?? 0 }}</div>
                                </span>
                            </div>
                        </div>

                        <!-- Valor, diferencia y botón de compra -->
                        <div class="flex flex-col gap-1 text-sm text-gray-700">
                            <div>
                                {{ number_format($player->valor_actual, 0, ',', '.') }} €
                                @if ($player->diferencia > 0)
                                    <span class="text-green-600 ml-1">▲
                                        {{ number_format($player->diferencia, 0, ',', '.') }} €</span>
                                @else
                                    <span class="text-red-600 ml-1">▼
                                        {{ number_format(abs($player->diferencia), 0, ',', '.') }} €</span>
                                @endif

                                <div class="mt-2 flex justify-end">
                                    <form method="POST" action="{{ route('jugador.comprar', $player->id) }}"
                                        class="form-comprar">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-success bg-gray-200 border border-gray-400 rounded-lg px-4 py-2 hover:bg-gray-300 transition">
                                            Comprar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    {{-- ——— FIN CONTENIDO DE MERCADO ——— --}}

    {{-- ——— MODAL DE CONFIRMACIÓN (DEBE IR FUERA DE TODO LO DEMÁS) ——— --}}
    <div id="confirm-modal" class="fixed inset-0 hidden items-center justify-center bg-blue-900">
        <div class="bg-blue-800 rounded-2xl shadow-xl w-11/12 md:w-1/2 lg:w-1/3 p-8">
            <h2 class="text-2xl font-bold text-white mb-4">Confirmar compra</h2>
            <p class="text-gray-200 mb-6">¿Estás seguro de que quieres comprar este jugador?</p>
            <div class="flex justify-end gap-4">
                <button id="cancel-btn"
                    class="px-4 py-2 rounded-lg border border-gray-400 bg-gray-200 hover:bg-gray-300 transition">
                    Cancelar
                </button>
                <button id="confirm-btn"
                    class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition">
                    Confirmar
                </button>
            </div>
        </div>
    </div>

    {{-- ——— SCRIPT PARA CONTROLAR EL MODAL ——— --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('confirm-modal');
            const confirmBtn = document.getElementById('confirm-btn');
            const cancelBtn = document.getElementById('cancel-btn');
            let activeForm = null;

            document.querySelectorAll('.form-comprar').forEach(form => {
                form.addEventListener('submit', e => {
                    e.preventDefault();
                    activeForm = form;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            cancelBtn.addEventListener('click', () => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                activeForm = null;
            });

            confirmBtn.addEventListener('click', () => {
                if (activeForm) activeForm.submit();
            });
        });
    </script>
</x-layouts.app>
