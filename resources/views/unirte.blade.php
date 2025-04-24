<!-- filepath: d:\DAW\Proyecte\elite-fantasy\resources\views\crear.blade.php -->
<x-layouts.app>
    <div class="min-h-screen bg-white flex flex-col items-center justify-start py-12 px-4">
        <div class="flex flex-wrap justify-center gap-6 w-full max-w-6xl">
            <!-- Tarjeta: Formulario Código -->
            <div class="relative text-black rounded-lg p-6 shadow-lg mt-4"
                style="background-color: #D9D9D9; width: 500px; height: auto;">
                <!-- Título -->
                <div class="flex items-center justify-between mb-6">
                    <span class="bg-[#3B0CA8] text-black px-4 py-2 rounded-md font-bold text-sm">CÓDIGO DE LIGA</span>
                </div>

                <!-- Formulario -->
                <form action="{{ route('league.code') }}" method="POST">
                    @csrf
                    <!-- Input de Código -->
                    <div class="mb-4">
                        <label for="league_code" class="block text-sm font-medium text-black">Código (formato
                            ABC-123)</label>
                        <input type="text" name="league_code" id="league_code" maxlength="7"
                            pattern="[A-Z0-9]{3}-[A-Z0-9]{3}" placeholder="ABC-123" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-lg px-4 py-3 bg-white text-gray-700 uppercase"
                            oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '').replace(/^(.{3})(.{0,3})/, '$1-$2')">
                    </div>

                    <!-- Contraseña -->
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-black">Contraseña</label>
                        <input type="password" name="password" id="password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-lg px-4 py-3 bg-white text-gray-700"
                            placeholder="Introduce la contraseña de la liga" required>
                    </div>

                    <!-- Botón -->
                    <div class="flex justify-center">
                        <button type="submit"
                            style="background-color: #4A5568; color: white; padding: 0.75rem 1.5rem; border-radius: 0.375rem; font-weight: 600; font-size: 1rem; transition: background-color 0.3s ease, transform 0.2s ease;"
                            onmouseover="this.style.backgroundColor='#2D3748'; this.style.transform='scale(1.05)';"
                            onmouseout="this.style.backgroundColor='#4A5568'; this.style.transform='scale(1)';">
                            Enviar Código
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>