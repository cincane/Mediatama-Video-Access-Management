<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Video Permitting') }} - Mediatama</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .glass-panel {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .glass-card {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.03);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover {
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 10px 30px -10px rgba(99, 102, 241, 0.15);
            transform: translateY(-2px);
        }
        .glow-indigo {
            box-shadow: 0 0 30px -5px rgba(99, 102, 241, 0.2);
        }
    </style>
</head>
<body class="h-full font-sans antialiased overflow-x-hidden">
    <div class="min-h-full flex flex-col md:flex-row">
        
        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 bg-slate-900/80 border-b md:border-b-0 md:border-r border-slate-800 flex flex-col flex-shrink-0">
            <div class="p-6 flex flex-col items-center border-b border-slate-800">
                <!-- Logo -->
                <div class="flex items-center justify-center w-full">
                    <img src="{{ Vite::asset('resources/img/logo2.png') }}" alt="Mediatama Logo" class="h-20 w-auto">
                </div>
                
                <!-- Teks di bawah logo -->
                <p class="text-md text-white font-extrabold tracking-wider mt-2 text-center">
                    Mediatama
                </p>
                <p class="text-xs text-white font-extrabold tracking-wider mt-2 text-center">
                    Video Access Management
                </p>
                
                <!-- Mobile Logout/Toggle placeholder if needed -->
                <div class="md:hidden absolute right-4 top-4">
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-red-400 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1">
                @auth
                    @if(auth()->user()->isAdmin())
                        <!-- Admin Navigation -->
                        <div class="text-slate-500 text-xs font-semibold px-3 uppercase tracking-wider mb-2">Admin Panel</div>
                        
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600/20 text-indigo-300 border-l-4 border-indigo-500 pl-3' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z" /></svg>
                            Dashboard
                        </a>

                        <a href="{{ route('admin.customers.index') }}" 
                           class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('admin.customers.*') ? 'bg-indigo-600/20 text-indigo-300 border-l-4 border-indigo-500 pl-3' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            Manage Customers
                        </a>

                        <a href="{{ route('admin.videos.index') }}" 
                           class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('admin.videos.*') ? 'bg-indigo-600/20 text-indigo-300 border-l-4 border-indigo-500 pl-3' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            Manage Videos
                        </a>

                        <a href="{{ route('admin.requests.index') }}" 
                           class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('admin.requests.*') ? 'bg-indigo-600/20 text-indigo-300 border-l-4 border-indigo-500 pl-3' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            Access Requests
                            @php
                                $pendingCount = \App\Models\VideoAccess::where('status', 'pending')->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="ml-auto inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-indigo-500 text-white animate-pulse">
                                    {{ $pendingCount }}
                                </span>
                            @endif
                        </a>
                    @else
                        <!-- Customer Navigation -->
                        <div class="text-slate-500 text-xs font-semibold px-3 uppercase tracking-wider mb-2">Customer Area</div>

                        <a href="{{ route('customer.dashboard') }}" 
                           class="flex items-center px-4 py-3 rounded-lg text-sm font-medium transition-all duration-150 {{ request()->routeIs('customer.dashboard') || request()->routeIs('customer.watch') ? 'bg-indigo-600/20 text-indigo-300 border-l-4 border-indigo-500 pl-3' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-200' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" /></svg>
                            Video Catalog
                        </a>
                    @endif
                @endauth
            </nav>

            <!-- User Info / Desktop Logout -->
            @auth
                <div class="p-4 border-t border-slate-800 hidden md:block">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-600/40 flex items-center justify-center text-indigo-300 font-bold border border-indigo-500/20">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400 capitalize truncate">{{ auth()->user()->role }}</p>
                        </div>
                    </div>
                    
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2.5 bg-slate-800 hover:bg-red-950/30 hover:text-red-400 hover:border-red-900/30 border border-slate-700/50 rounded-lg text-sm text-slate-300 transition-all font-medium">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            @endauth
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0 bg-[#eaeafd]">
            <!-- Top bar -->
            <header class="h-16 border-b bg-slate-900/100 border-slate-800 flex items-center justify-between px-6 md:px-8">
                <div class="flex items-center">
                    <span class="text-white text-lg font-extrabold mr-2">Mediatama</span>
                    
                </div>
                
                @auth
                    <div class="flex items-center md:hidden">
                        <div class="text-right mr-3">
                            <p class="text-sm font-semibold text-white leading-none">{{ auth()->user()->name }}</p>
                            <span class="text-[10px] text-indigo-400 uppercase tracking-wider font-bold">{{ auth()->user()->role }}</span>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-indigo-600/40 flex items-center justify-center text-indigo-300 font-bold border border-indigo-500/20">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                @endauth
            </header>

            <!-- Page Content -->
            <div class="flex-1 p-6 md:p-8 overflow-y-auto">
                <!-- Notifications -->
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-lg bg-emerald-950/30 border border-emerald-900/30 text-black flex items-start space-x-3 glow-indigo">
                        <svg class="w-5 h-5 text-green-700 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-lg bg-rose-950/30 border border-rose-900/30 text-rose-400 flex items-start space-x-3">
                        <svg class="w-5 h-5 text-rose-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Yield Scripts if needed -->
    @yield('scripts')
</body>
</html>
