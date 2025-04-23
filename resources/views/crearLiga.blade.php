<x-layouts.app>
    <div class="min-h-screen bg-white flex flex-col items-center justify-start py-12 px-4">

        <div class="flex flex-wrap justify-center gap-6 w-full max-w-6xl">
            <!-- Tarjeta: Crear Liga -->
            <div class="relative text-black rounded-lg p-6 shadow-lg mt-4"
                style="background-color: #AD97C8; width: 500px; height: 220px;">
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
                    <a href="{{ route('crear.form') }}"
                        style="background-color: #4A5568; color: white; padding: 0.75rem 1.5rem; border-radius: 0.375rem; font-weight: 600; font-size: 1rem; text-align: center; display: inline-block; transition: background-color 0.3s ease, transform 0.2s ease;"
                        onmouseover="this.style.backgroundColor='#2D3748'; this.style.transform='scale(1.05)';"
                        onmouseout="this.style.backgroundColor='#4A5568'; this.style.transform='scale(1)';">
                        CREAR
                    </a>
                </div>
            </div>
            <!-- Tarjeta: Unirse a Liga -->
            <div class="relative text-black rounded-lg p-6 shadow-lg mt-4"
                style="background-color: #AD97C8; width: 500px; height: 220px;">
                <!-- Título -->
                <div class="flex items-center justify-between mb-6">
                    <span class="bg-[#3B0CA8] text-white px-4 py-2 rounded-md font-bold text-sm">UNIRTE A UNA
                        LIGA</span>
                    <span class="text-2xl font-bold text-black">→</span>
                </div>

                <!-- Descripción -->
                <p class="text-sm mb-6 text-left">
                    Únete a una liga ya creada a partir del código de invitación.
                </p>

                <!-- Botón -->
                <div class="flex justify-center">
                    <a href="{{ route('unir.form') }}"
                        style="background-color: #4A5568; color: white; padding: 0.75rem 1.5rem; border-radius: 0.375rem; font-weight: 600; font-size: 1rem; text-align: center; display: inline-block; transition: background-color 0.3s ease, transform 0.2s ease;"
                        onmouseover="this.style.backgroundColor='#2D3748'; this.style.transform='scale(1.05)';"
                        onmouseout="this.style.backgroundColor='#4A5568'; this.style.transform='scale(1)';">
                        UNIRTE
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
