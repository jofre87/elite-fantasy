<div class="flex flex-col items-center p-2">
    <img src="{{ $jugador->jugador->imagen }}" alt="{{ $jugador->jugador->nombre }}"
        class="w-14 h-14 rounded-full mb-1 shadow-md">

    <span class="text-sm font-semibold text-black text-center">
        {{ $jugador->jugador->nombre }}
    </span>

    <div class="mt-1 text-xs text-white bg-green-600 px-2 py-0.5 rounded-full shadow-sm border border-white">
        {{ $jugador->puntos ?? 0 }} pts
    </div>
</div>