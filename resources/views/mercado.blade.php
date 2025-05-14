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
                        $racha = $estadisticas->racha_puntos ?? [];
                        $ultimosCinco = is_array($racha) ? array_slice($racha, -5) : [];
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

                            {{-- Últimas 5 jornadas con colores desde racha_puntos --}}
                            <div class="flex gap-1 mt-2">
                                @foreach ($ultimosCinco as $p)
                                    @php
                                        $color = 'bg-gray-400';
                                        if ($p < 0) {
                                            $color = 'bg-red-500';
                                        } elseif ($p >= 2 && $p <= 9) {
                                            $color = 'bg-green-500';
                                        } elseif ($p > 9) {
                                            $color = 'bg-blue-500';
                                        }
                                    @endphp
                                    <span
                                        class="w-6 h-6 rounded-full text-white text-xs flex items-center justify-center {{ $color }}">
                                        {{ $p }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-2 flex justify-end">
                            <form method="POST" action="{{ route('jugador.comprar', $player->id) }}"
                                class="form-comprar">
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
</x-layouts.app>
