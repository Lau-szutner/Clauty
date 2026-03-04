<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Clauty :: {{ $title }} </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="clauty-icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <style>
        .menu-open { overflow: hidden; }
    </style>
</head>

<body class="font-sans bg-slate-50 min-h-screen flex flex-col text-slate-900">

    {{-- NAVBAR --}}
    <nav class="sticky top-0 z-[60] bg-white/80 backdrop-blur-md border-b border-slate-200 px-6 md:px-8 py-5 transition-all duration-300">
        <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-3 items-center">
            
            {{-- COLUMNA 1: LOGO --}}
            <div class="flex justify-start">
                <h1 class="text-3xl font-serif font-bold tracking-tighter text-slate-900">
                    Clauty<span class="text-indigo-600">.</span>
                </h1>
            </div>

            {{-- COLUMNA 2: MENÚ CENTRAL (Escritorio) --}}
            <ul class="hidden md:flex gap-10 justify-center items-center">
                <li class="group">
                    <x-nav-link route="home" class="text-sm uppercase tracking-[0.2em] font-medium text-slate-500 hover:text-indigo-600 transition-colors">Home</x-nav-link>
                </li>
                <li class="group">
                    <x-nav-link route="cursos.index" class="text-sm uppercase tracking-[0.2em] font-medium text-slate-500 hover:text-indigo-600 transition-colors">Cursos</x-nav-link>
                </li>
                <li class="group">
                    <x-nav-link route="articulos.index" class="text-sm uppercase tracking-[0.2em] font-medium text-slate-500 hover:text-indigo-600 transition-colors">Artículos</x-nav-link>
                </li>
                <li class="group relative">
                    <x-nav-link route="suscripciones" class="text-sm uppercase tracking-[0.2em] font-medium text-slate-500 hover:text-indigo-600 transition-colors flex items-center gap-2">
                        Planes
                        <span class="h-1.5 w-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                    </x-nav-link>
                </li>
            </ul>

            {{-- COLUMNA 3: ACCIONES / HAMBURGUESA --}}
            <div class="flex justify-end items-center gap-4">
                {{-- Auth Desktop --}}
                @auth
                <div class="hidden md:flex items-center gap-4 border-l pl-6 border-slate-200">
                    <div class="text-right hidden lg:block">
                        <p class="text-[9px] uppercase tracking-widest text-slate-400 font-bold leading-none mb-1">Conectado</p>
                        <p class="text-xs font-semibold text-slate-700 leading-none">{{ auth()->user()->email }}</p>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ auth()->user()->rol === 'admin' ? route('dashboard') : route('student.dashboard') }}"
                            class="px-4 py-2 text-sm font-bold uppercase tracking-widest bg-slate-900 text-white rounded-full hover:bg-indigo-600 transition-all">Perfil</a>

                        <form action="{{ route('admin.doLogout') }}" method="post" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition-colors" title="Cerrar sesión">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" class="hidden md:block group relative px-8 py-3 overflow-hidden rounded-full bg-indigo-600 text-white transition-all duration-300 hover:shadow-lg hover:shadow-indigo-200">
                    <span class="relative z-10 text-sm font-bold uppercase tracking-widest">Login</span>
                    <div class="absolute inset-0 h-full w-full bg-slate-900 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-500"></div>
                </a>
                @endauth

                {{-- Botón Hamburguesa (Móvil) --}}
                <button id="menu-toggle" class="md:hidden p-2 text-slate-900 focus:outline-none z-[70]">
                    <svg id="menu-icon" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    {{-- MENÚ MÓVIL (OVERLAY) --}}
    <div id="mobile-menu" class="fixed inset-0 z-[55] bg-white translate-x-full transition-transform duration-500 ease-in-out md:hidden flex flex-col pt-32 px-10">
        <ul class="flex flex-col gap-8">
            <li><x-nav-link route="home" class="text-2xl font-serif font-bold text-slate-900">Home</x-nav-link></li>
            <li><x-nav-link route="cursos.index" class="text-2xl font-serif font-bold text-slate-900">Cursos</x-nav-link></li>
            <li><x-nav-link route="articulos.index" class="text-2xl font-serif font-bold text-slate-900">Artículos</x-nav-link></li>
            <li><x-nav-link route="suscripciones" class="text-2xl font-serif font-bold text-indigo-600">Planes</x-nav-link></li>
        </ul>

        <div class="mt-auto mb-16 pt-8 border-t border-slate-100 flex flex-col gap-6">
            @auth
                <div class="bg-slate-50 p-4 rounded-2xl flex items-center justify-between">
                    <div class="overflow-hidden">
                        <p class="text-[10px] uppercase font-bold text-slate-400">Usuario</p>
                        <p class="text-xs font-semibold text-slate-700 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form action="{{ route('admin.doLogout') }}" method="post">
                        @csrf
                        <button type="submit" class="p-3 text-red-500 bg-white rounded-xl shadow-sm border border-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
                <a href="{{ auth()->user()->rol === 'admin' ? route('dashboard') : route('student.dashboard') }}" 
                   class="w-full text-center py-4 bg-slate-900 text-white rounded-2xl font-bold uppercase tracking-widest">Ir al Panel</a>
            @else
                <a href="{{ route('login') }}" class="w-full text-center py-5 bg-indigo-600 text-white rounded-2xl font-bold uppercase tracking-widest shadow-xl shadow-indigo-100">Login</a>
            @endauth
        </div>
    </div>

    {{-- FEEDBACK MESSAGES --}}
    @if (session()->has('feedback.message'))
    <div class="fixed bottom-10 right-10 z-[100] animate-bounce">
        <div class="px-6 py-4 rounded-2xl shadow-2xl border flex items-center gap-4 
            @if (session()->get('feedback.type') == 'error') bg-white border-red-100 text-red-600 
            @else bg-white border-emerald-100 text-emerald-600 @endif">
            <div class="h-2 w-2 rounded-full @if (session()->get('feedback.type') == 'error') bg-red-500 @else bg-emerald-500 @endif"></div>
            <span class="text-sm font-medium italic">{!! session()->get('feedback.message') !!}</span>
        </div>
    </div>
    @endif

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="flex-grow">
        {{ $slot }}
    </main>

    {{-- FOOTER --}}
    <footer class="w-full bg-white border-t border-slate-200 py-12">
        <div class="container mx-auto px-8 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="text-2xl font-serif font-bold tracking-tighter text-slate-900">
                Clauty<span class="text-indigo-600">.</span>
            </div>

            <div class="flex flex-col md:flex-row gap-8 text-[10px] uppercase tracking-[0.3em] text-slate-400 font-bold">
                <div class="flex items-center gap-2">
                    <span class="text-slate-300">Estudiante</span>
                    <span class="text-slate-600">Lautaro Fernandez Szutner</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-slate-300">Comisión</span>
                    <span class="text-slate-600">DWN3AV</span>
                </div>
            </div>

            <div class="text-slate-300 text-xs text-center md:text-right">
                &copy; {{ date('Y') }} Todos los derechos reservados.
            </div>
        </div>
    </footer>

    {{-- SCRIPTS --}}
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const body = document.body;

        menuToggle.addEventListener('click', () => {
            const isOpen = !mobileMenu.classList.contains('translate-x-full');

            if (!isOpen) {
                // Abrir
                mobileMenu.classList.remove('translate-x-full');
                body.classList.add('menu-open');
                menuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
            } else {
                // Cerrar
                mobileMenu.classList.add('translate-x-full');
                body.classList.remove('menu-open');
                menuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />';
            }
        });
    </script>

    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        const publicKey = "APP_USR-9137f035-9de4-41a1-939a-a9d2945baf8a";
        const preferenceId = "3202141953-5f840558-b090-4ece-9552-cd4dc8408f80";
        const mp = new MercadoPago(publicKey);
        const bricksBuilder = mp.bricks();

        const renderWalletBrick = async (bricksBuilder) => {
            if (document.getElementById('walletBrick_container')) {
                await bricksBuilder.create("wallet", "walletBrick_container", {
                    initialization: {
                        preferenceId: preferenceId,
                    }
                });
            }
        };
        renderWalletBrick(bricksBuilder);
    </script>
</body>

</html>