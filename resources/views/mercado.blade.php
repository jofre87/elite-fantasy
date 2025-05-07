<x-layouts.app :title="__('Mercado')">
    <div
        class="bg-purple-700 text-black p-6 rounded-2xl shadow-lg flex flex-col sm:flex-row justify-between items-center border-2 border-purple-800 mb-6">
        <div class="text-center sm:text-left">
            <p class="text-sm">Usuario:</p>
            <p class="text-2xl font-bold">
                {{ $ligaUser->user->name ?? 'Sin nombre' }}
            </p>
            <p class="text-sm mt-1">
                {{ $ligaUser->user->puntos ?? '0' }} pts |
                {{ number_format($ligaUser->saldo, 2, ',', '.') }} €
            </p>
        </div>
        <div class="mt-4 sm:mt-0 text-center sm:text-right">
            <p class="text-sm">Valor de tu equipo:</p>
            <p class="text-2xl font-bold">65M€</p> {{-- Puedes calcular el valor real si lo deseas --}}
        </div>
    </div>

    {{-- CONTENIDO DEL MERCADO --}}
    <div class="flex h-full w-full flex-1 flex-col gap-4 px-4">
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
                                            class="btn bg-gray-200 border border-gray-400 rounded-lg px-4 py-2 hover:bg-gray-300 transition">
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

    {{-- MODAL DE CONFIRMACIÓN --}}
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

    {{-- SCRIPT DEL MODAL --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('confirm-modal');
            const confirmBtn = document.getElementById('confirm-btn');
            let activeForm = null;

            document.querySelectorAll('.form-comprar').forEach(form => {
                form.addEventListener('submit', e => {
                    e.preventDefault();
                    activeForm = form;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                });
            });

            window.cerrarModal = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                activeForm = null;
            };

            confirmBtn.addEventListener('click', () => {
                if (activeForm) activeForm.submit();
            });
        });
    </script>
</x-layouts.app>
