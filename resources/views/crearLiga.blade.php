<x-layouts.app>
    <div class="min-h-screen bg-white flex flex-col items-center justify-start py-12 px-4">

        <div class="flex flex-wrap justify-center gap-6 w-full max-w-6xl">
            <!-- Tarjeta: Crear Liga -->
            <div class="relative text-black rounded-lg p-6 w-[600px] h-[700px] shadow-lg mt-4" style="background-color: #AD97C8;">
                <!-- Título -->
                <div class="flex items-center justify-between mb-6">
                    <span class="bg-[#3B0CA8] text-white px-4 py-2 rounded-md font-bold text-sm">CREAR LIGA</span>
                    <span class="text-2xl font-bold text-black">+</span>
                </div>

                <!-- Descripción -->
                <p class="text-sm mb-6 text-left">
                    Crear tu propia liga e invita a tus amigos. Disfruta de tener a los <br>
                    mejores jugadores en tu equipo.
                </p>

                <!-- Botón -->
                <div class="flex justify-center">
                    <button class="bg-gray-800 text-white px-6 py-3 rounded-md font-semibold hover:bg-gray-900 text-base">
                        CREAR
                    </button>
                </div>
            </div>

            <!-- Tarjeta: Unirse a Liga -->
            <div class="relative text-black rounded-lg p-6 w-[600px] h-[700px] shadow-lg mt-4" style="background-color: #AD97C8;">
                <!-- Título -->
                <div class="flex items-center justify-between mb-6">
                    <span class="bg-[#3B0CA8] text-white px-4 py-2 rounded-md font-bold text-sm">UNIRTE A UNA LIGA</span>
                    <span class="text-2xl font-bold text-black">→</span>
                </div>

                <!-- Descripción -->
                <p class="text-sm mb-6 text-left">
                    Únete a una liga ya creada a partir del código de invitación.
                </p>

                <!-- Botón -->
                <div class="flex justify-center">
                    <button class="bg-gray-800 text-white px-6 py-3 rounded-md font-semibold hover:bg-gray-900 text-base">
                        UNIRTE
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>