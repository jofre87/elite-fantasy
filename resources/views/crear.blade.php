<!-- filepath: d:\DAW\Proyecte\elite-fantasy\resources\views\crear.blade.php -->
<x-layouts.app>
    <div class="min-h-screen bg-white flex flex-col items-center justify-start py-12 px-4">
        <div class="flex flex-wrap justify-center gap-6 w-full max-w-6xl">
            <!-- Tarjeta: Formulario de Crear Liga -->
            <div class="relative text-black rounded-lg p-6 shadow-lg mt-4"
                style="background-color: #D9D9D9; width: 500px; height: auto;">
                <!-- Título -->
                <div class="flex items-center justify-between mb-6">
                    <span class="bg-[#3B0CA8] text-black px-4 py-2 rounded-md font-bold text-sm">CREAR LIGA</span>
                </div>

                <!-- Formulario -->
                <form action="{{ route('league.store') }}" method="POST">
                    @csrf
                    <!-- Nombre de la Liga -->
                    <div class="mb-4">
                        <label for="league_name" class="block text-sm font-medium text-black">Nombre de la Liga</label>
                        <input type="text" name="league_name" id="league_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-lg px-4 py-3 bg-white text-gray-700"
                            placeholder="Introduce el nombre de la liga" required>
                    </div>

                    <!-- Contraseña -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-black">Contraseña</label>
                        <input type="password" name="password" id="password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-lg px-4 py-3 bg-white text-gray-700"
                            placeholder="Introduce la contraseña para unirte a la liga" required>
                    </div>

                    <!-- Reparto Inicial -->
                    <div class="mb-4">
                        <label for="initial_share" class="block text-sm font-medium text-black">Reparto Inicial</label>
                        <input type="number" name="initial_share" id="initial_share"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-lg px-4 py-3 bg-white text-gray-700"
                            placeholder="Introduce el reparto inicial en número" required>
                    </div>

                    <!-- Botón de Crear -->
                    <div class="flex justify-center">
                        <button type="submit"
                            style="background-color: #4A5568; color: white; padding: 0.75rem 1.5rem; border-radius: 0.375rem; font-weight: 600; font-size: 1rem; transition: background-color 0.3s ease, transform 0.2s ease;"
                            onmouseover="this.style.backgroundColor='#2D3748'; this.style.transform='scale(1.05)';"
                            onmouseout="this.style.backgroundColor='#4A5568'; this.style.transform='scale(1)';">
                            Crear Liga
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>