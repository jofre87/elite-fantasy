<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    <style>
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: blue;
            color: white;
        }


        .logo {
            font-weight: bold;
            font-size: 20px;
            letter-spacing: 1px;
            display: flex;
            gap: 10px;
        }

        .user-area {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-links {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 20px;
        }

        .nav-links a:hover {
            color: gray;
        }

        body.dark .nav-links a:hover {
            color: gray;
        }

        /* Estilos para el menú de usuario (opcional) */
        .user-menu {
            position: relative;
        }

        .user-dropdown {
            position: absolute;
            right: 0;
            top: 100%;
            background-color: blue;
            border: 1px solid white;
            border-radius: 4px;
            padding: 5px 0;
            min-width: 150px;
            display: none;
        }

        .user-dropdown a,
        .user-dropdown button {
            display: block;
            padding: 8px 15px;
            text-decoration: none;
            color: white;
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            cursor: pointer;
        }

        body.dark .user-dropdown a,
        body.dark .user-dropdown button {
            color: white;
        }

        .user-dropdown a:hover,
        .user-dropdown button:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }

        body.dark .user-dropdown a:hover,
        body.dark .user-dropdown button:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
    </style>
</head>

<body class="min-h-screen">
    <header class="header flex items-center justify-between p-4 bg-blue-600 text-white">
        <div class="logo-container flex flex-col items-start"> <!-- Contenedor para el logo y el botón -->
            <div class="logo">
                @include('components.app-logo')
            </div>
            <!-- filepath: d:\DAW\Proyecte\elite-fantasy\resources\views\components\header.blade.php -->
            <div class="menu-and-user flex items-center gap-4 mt-2">
                <!-- Botón desplegable -->
                <div class="relative" x-data="{ open: false }">
                    <button class="menu-button text-white bg-blue-700 p-3 rounded-md" @click="open = !open">
                        ☰
                    </button>
                    <!-- Opciones del desplegable -->
                    <div class="absolute right-0 mt-2 w-48 bg-white text-black rounded-md shadow-lg z-10" x-show="open"
                        @click.away="open = false">
                        <a href="{{ route('settings.profile') }}" class="block px-4 py-2 hover:bg-gray-200">Perfil</a>
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-200">Dashboard</a>
                    </div>
                </div>
                <div class="user-menu" x-data="{ open: false }">
                    <div class="username cursor-pointer" @click="open = !open">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="user-dropdown" x-show="open" @click.away="open = false">
                        <a href="{{ route('settings.profile') }}">Settings</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- filepath: d:\DAW\Proyecte\elite-fantasy\resources\views\components\header.blade.php -->
        <div class="user-area flex items-center gap-4 ml-auto mr-8"> <!-- Ajuste de márgenes -->
            <nav>
                <ul class="nav-links hidden lg:flex">
                    <li class="flex flex-col items-center">
                        <a href="{{ route('dashboard') }}" class="flex flex-col items-center">
                            <img src="/img/inicio.png" alt="Inicio" class="w-10 h-8 mb-2"> <!-- Más ancho -->
                            <span class="text-sm font-medium text-white hover:text-gray-300">Inicio</span>
                        </a>
                    </li>
                    <li class="flex flex-col items-center">
                        <a href="#" class="flex flex-col items-center">
                            <img src="/img/plantilla.png" alt="Plantilla" class="w-8 h-8 mb-2">
                            <span class="text-sm font-medium text-white hover:text-gray-300">Plantilla</span>
                        </a>
                    </li>
                    <li class="flex flex-col items-center">
                        <a href="#" class="flex flex-col items-center">
                            <img src="/img/mercado.png" alt="Mercado" class="w-8 h-8 mb-2">
                            <span class="text-sm font-medium text-white hover:text-gray-300">Mercado</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>


    {{ $slot }}

    @fluxScripts
</body>

</html>