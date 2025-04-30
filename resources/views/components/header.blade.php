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

        /* Estilos para el menú de usuario */
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
            padding: 10px 0;
            min-width: 200px;
            display: none;
            z-index: 20;
        }

        .user-dropdown a,
        .user-dropdown button {
            display: block;
            padding: 10px 15px;
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

        /* Diferenciar las opciones */
        .user-dropdown a,
        .user-dropdown button {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-dropdown button:last-child,
        .user-dropdown a:last-child {
            border-bottom: none;
        }

        .user-dropdown .logout {
            background-color: red;
            color: white;
        }

        .user-dropdown .logout:hover {
            background-color: darkred;
        }
    </style>
</head>

<body class="min-h-screen">
    <header class="header flex items-center justify-between p-4 bg-blue-600 text-white">
        <div class="logo-container flex flex-col items-start">
            <div class="logo">
                @include('components.app-logo')
            </div>

            <div class="menu-and-user flex items-center gap-4 mt-2">
                <!-- Menú hamburguesa -->
                <div class="relative" x-data="{ open: false }">
                    <button class="menu-button text-white bg-blue-700 p-3 rounded-md" @click="open = !open">
                        ☰
                    </button>
                    <!-- Menú desplegable -->
                    <div class="absolute left-0 mt-2 w-56 bg-white dark:bg-blue-800 text-black dark:text-white rounded-md shadow-lg z-10"
                        x-show="open" @click.away="open = false">
                        <a href="{{ route('settings.profile') }}"
                            class="block px-4 py-2 hover:bg-gray-200 dark:hover:bg-blue-700">Perfil</a>
                        <a href="{{ route('dashboard') }}"
                            class="block px-4 py-2 hover:bg-gray-200 dark:hover:bg-blue-700">Dashboard</a>
                        <a href="{{ route('league.create') }}"
                            class="block px-4 py-2 hover:bg-gray-200 dark:hover:bg-blue-700">Crear o Unirse a Liga</a>
                    </div>
                </div>

                <!-- Usuario y logout -->
                <div class="user-menu relative" x-data="{ open: false }">
                    <div class="username cursor-pointer font-semibold" @click="open = !open">
                        {{ auth()->user()->name }}
                    </div>
                    <div class="user-dropdown absolute right-0 mt-2 bg-blue-600 text-white rounded shadow-lg z-20"
                        x-show="open" @click.away="open = false">
                        <a href="{{ route('settings.profile') }}" class="block px-4 py-2 hover:bg-blue-700">Ajustes</a>
                        <a href="{{ route('league.create') }}" class="block px-4 py-2 hover:bg-blue-700">Crear/Unirse a
                            Liga</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block px-4 py-2 logout">Cerrar sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menú de navegación -->
        <div class="user-area flex items-center gap-4 ml-auto mr-8">
            <nav>
                <ul class="nav-links hidden lg:flex">
                    <li class="flex flex-col items-center">
                        <a href="{{ route('dashboard') }}" class="flex flex-col items-center">
                            <img src="/img/inicio.png" alt="Inicio" class="w-10 h-8 mb-2">
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
                        <a href="{{ route('market.index') }}" class="flex flex-col items-center">
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
