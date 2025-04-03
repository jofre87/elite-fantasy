<!-- filepath: d:\DAW\Proyecte\elite-fantasy\resources\views\crearLiga.blade.php -->
<x-layouts.app>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
        <h1 class="text-2xl font-bold mb-8">Gestión de Ligas</h1>

        <!-- Contenedor para alinear los divs horizontalmente -->
        <div class="flex flex-wrap justify-center gap-8">
            <!-- Div para crear una liga -->
            <div class="w-full max-w-md bg-blue-100 p-6 rounded-lg shadow-md">
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

            <!-- Div para unirse a una liga -->
            <div class="w-full max-w-md bg-green-100 p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 text-green-700">Unirse a una Liga</h2>
                <form action="{{ route('league.join') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="league_code" class="block text-sm font-medium text-gray-700">Código de la Liga</label>
                        <input type="text" name="league_code" id="league_code"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                            required>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Unirse</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>