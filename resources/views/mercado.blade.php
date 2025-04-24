<x-layouts.app :title="__('Mercado')">
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <!-- Filtros -->
        <div class="flex justify-center gap-4 bg-blue-900 p-4 rounded-xl">
            <button class="bg-gray-200 text-black px-4 py-2 rounded-md hover:bg-blue-200 transition">Mercado</button>
            <button class="bg-gray-200 text-black px-4 py-2 rounded-md hover:bg-blue-200 transition">Ventas</button>
            <button class="bg-gray-200 text-black px-4 py-2 rounded-md hover:bg-blue-200 transition">Ofertas</button>
            <button class="bg-gray-200 text-black px-4 py-2 rounded-md hover:bg-blue-200 transition">Pujas</button>
        </div>

        <!-- Grid de jugadores -->
        <div class="p-4 bg-blue-900 rounded-xl flex-1 overflow-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($players as $player)
                    <div class="bg-white text-gray-800 rounded-xl p-4 flex items-center justify-between shadow-md">
                        <!-- Avatar + nombre + dorsal -->
                        <div class="flex items-center gap-3">
                            <img class="w-12 h-12 rounded-full border border-gray-300" 
                                 src="{{ $player->imagen }}" 
                                 alt="{{ $player->nombre }}">
                            <div>
                                <div class="text-sm font-semibold">{{ $player->nombre }}</div>
                                <div class="text-xs text-gray-600">#{{ $player->dorsal ?? '-' }}</div>
                            </div>
                        </div>
                        <!-- Precio y acción -->
                        <div class="flex flex-col items-end gap-2">
                            <div class="text-sm font-bold">
                                {{ number_format($player->valor_actual, 0, ',', '.') }}€
                            </div>
                            <button 
                                class="bg-blue-700 text-white text-xs px-3 py-1 rounded hover:bg-blue-800 transition"
                                @click="$dispatch('open-bid-modal', {{ $player->id }})"
                            >
                                Pujar
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>
