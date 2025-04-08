<x-layouts.app>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
        <h1 class="text-2xl font-bold mb-8">Gestión de Ligas</h1>

        <!-- Contenedor principal para alinear los divs horizontalmente -->
        <div class="flex flex-wrap justify-center gap-8">
            <!-- Contenedor con fondo para crear una liga -->
            <div class="w-full max-w-md p-6 rounded-lg shadow-md bg-blue-50">
                <h2 class="text-xl font-semibold mb-4 text-blue-700">Crear Liga</h2>
                <form action="{{ route('league.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="league_name" class="block text-sm font-medium text-gray-700">Nombre de la Liga</label>
                        <input type="text" name="league_name" id="league_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Crear</button>
                    </div>
                </form>
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