<!-- resources/views/crearLiga.blade.php -->
<x-layouts.app>
    <div class="flex flex-col items-center justify-center min-h-screen">
        <h1 class="text-2xl font-bold mb-4">Crear Liga</h1>
        <form action="{{ route('league.store') }}" method="POST"
            class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
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
</x-layouts.app>