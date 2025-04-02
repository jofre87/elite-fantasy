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
    <header class="header">
        <div class="logo">
            <span>ELITE FANTASY</span>
        </div>

        <div class="user-area">
            <div class="user-menu" x-data="{ open: false }">
                <div class="username" @click="open = !open">{{ auth()->user()->name }}</div>
                <div class="user-dropdown" x-show="open" @click.away="open = false">
                    <a href="{{ route('settings.profile') }}">Settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Log Out</button>
                    </form>
                </div>
            </div>
            <nav>
                <ul class="nav-links hidden lg:flex">
                    <li><a href="{{ route('dashboard') }}">Inicio</a></li>
                    <li><a href="#">Plantilla</a></li>
                    <li><a href="#">Mercado</a></li>
                </ul>
                <button class="lg:hidden">☰</button>
            </nav>
        </div>
    </header>

    {{ $slot }}

    @fluxScripts
</body>

</html>