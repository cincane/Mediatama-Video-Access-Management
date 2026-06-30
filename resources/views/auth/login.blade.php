<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Mediatama Video Hub</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .glass-login {
            background: rgba(30, 41, 59, 0.45);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient-shift 15s ease infinite;
        }
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body class="h-full font-sans antialiased flex items-center justify-center relative overflow-hidden bg-slate-950">
    
    <!-- Decorative Glow Circles -->
    <div class="absolute -top-40 -left-40 w-96 h-96 rounded-full bg-indigo-600/20 blur-3xl"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 rounded-full bg-violet-600/20 blur-3xl"></div>

    <div class="w-full max-w-md p-6 relative z-10">
        
        <!-- Logo Header -->
        <div class="flex flex-col items-center mb-8">
            <div class="flex items-center justify-center w-full">
                <img src="{{ Vite::asset('resources/img/logo2.png') }}" alt="Mediatama Logo" class="h-20 w-auto">
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-white">Mediatama</h1>
            <h1 class="text-2xl font-bold tracking-tight text-white">Video Access Management</h1>
            <p class="text-sm text-slate-400 mt-3">Sign in to request or manage video viewing access</p>
        </div>

        <!-- Login Card -->
        <div class="glass-login rounded-2xl p-8 shadow-2xl relative overflow-hidden">
            <!-- Glow Accent Line -->
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-violet-500"></div>

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Session error / success messages -->
                @if($errors->any())
                    <div class="p-4 rounded-lg bg-rose-950/40 border border-rose-900/30 text-rose-400 text-sm">
                        @foreach($errors->all() as $error)
                            <p class="font-medium">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                
                @if(session('success'))
                    <div class="p-4 rounded-lg bg-emerald-950/40 border border-emerald-900/30 text-emerald-400 text-sm">
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Email field -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <svg class="h-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" /></svg>
                        </span>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                            class="block w-full pl-10 pr-4 py-3 bg-slate-900/60 border border-slate-800 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm"
                            placeholder="you@example.com">
                    </div>
                </div>

                <!-- Password field -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <svg class="h-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </span>
                        <input id="password" name="password" type="password" required
                            class="block w-full pl-10 pr-4 py-3 bg-slate-900/60 border border-slate-800 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all text-sm"
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Remember Me -->
                {{-- <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 rounded bg-slate-900 border-slate-850 text-indigo-600 focus:ring-indigo-500/30 focus:ring-offset-slate-950">
                    <label for="remember" class="ml-2 block text-sm text-slate-400 select-none">Remember my session</label>
                </div> --}}

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/30 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500/50 cursor-pointer">
                        Sign In
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Hint footer (useful for evaluators) -->
        {{-- <div class="mt-6 text-center text-xs text-slate-500 bg-slate-900/40 backdrop-blur-sm p-4 rounded-xl border border-slate-800/40">
            <p class="font-semibold text-slate-400 mb-1 text-[11px] uppercase tracking-wider">Test Credentials</p>
            <div class="flex justify-around mt-2">
                <div>
                    <p class="font-medium text-slate-400">Admin</p>
                    <code class="text-[10px] text-indigo-400">admin@mediatama.com</code>
                    <p class="text-[10px] text-slate-500">password: <span class="text-indigo-400">password</span></p>
                </div>
                <div class="border-r border-slate-800"></div>
                <div>
                    <p class="font-medium text-slate-400">Customer</p>
                    <code class="text-[10px] text-indigo-400">customer@mediatama.com</code>
                    <p class="text-[10px] text-slate-500">password: <span class="text-indigo-400">password</span></p>
                </div>
            </div>
        </div> --}}
    </div>
</body>
</html>
