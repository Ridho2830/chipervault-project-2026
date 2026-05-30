<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'CipherVault') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Premium Glassmorphism */
        .glass {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }
        .glass-panel {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-top: 1px solid rgba(255, 255, 255, 0.15);
        }
        /* Subtle animated background */
        .bg-animated {
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
    </style>
</head>
<body class="bg-[#0b1120] bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-[#0b1120] to-black text-gray-200 min-h-screen flex flex-col antialiased selection:bg-blue-500/30 selection:text-blue-200">

    <nav class="glass sticky top-0 z-50 px-6 py-4 flex justify-between items-center transition-all duration-300">
        <a href="/" class="text-2xl font-bold flex items-center gap-3 group">
            <div class="p-2 bg-blue-500/10 rounded-xl group-hover:bg-blue-500/20 transition-colors border border-blue-500/20">
                <svg class="w-6 h-6 text-blue-400 group-hover:text-blue-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-white via-gray-200 to-gray-400 group-hover:from-blue-400 group-hover:to-emerald-400 transition-all duration-500">CipherVault</span>
        </a>
        
        <div>
            @auth
                <div class="flex gap-6 items-center">
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors relative after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:origin-bottom-right after:scale-x-0 after:bg-blue-400 after:transition-transform after:duration-300 hover:after:origin-bottom-left hover:after:scale-x-100 pb-1">Dashboard</a>
                    <a href="{{ route('files.index') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors relative after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:origin-bottom-right after:scale-x-0 after:bg-blue-400 after:transition-transform after:duration-300 hover:after:origin-bottom-left hover:after:scale-x-100 pb-1">Files</a>
                    <a href="{{ route('notes.index') }}" class="text-sm font-medium text-gray-400 hover:text-white transition-colors relative after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:origin-bottom-right after:scale-x-0 after:bg-blue-400 after:transition-transform after:duration-300 hover:after:origin-bottom-left hover:after:scale-x-100 pb-1">Notes</a>
                    
                    <form method="POST" action="{{ route('logout') }}" class="inline ml-2">
                        @csrf
                        <button type="submit" class="bg-slate-800 hover:bg-slate-700 border border-slate-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition-all shadow-lg hover:shadow-slate-700/50 focus:ring-2 focus:ring-slate-500 outline-none">Logout</button>
                    </form>
                </div>
            @else
                <div class="flex gap-4 items-center">
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-300 hover:text-white transition-colors">Log in</a>
                    <a href="{{ route('register') }}" class="text-sm font-medium bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-400 hover:to-indigo-500 text-white px-5 py-2.5 rounded-xl transition-all shadow-[0_0_20px_rgba(59,130,246,0.3)] hover:shadow-[0_0_25px_rgba(59,130,246,0.5)] transform hover:-translate-y-0.5">Get Started</a>
                </div>
            @endauth
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-4 py-8 md:py-12 flex flex-col">
        @yield('content')
    </main>

    <footer class="border-t border-white/5 py-8 mt-auto">
        <div class="container mx-auto px-4 text-center">
            <p class="text-gray-500 text-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                &copy; {{ date('Y') }} CipherVault. End-to-End Encrypted.
            </p>
        </div>
    </footer>

    <script>
        window.fetchConfig = {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        };
        
        window.showToast = function(title, icon = 'success') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: icon,
                title: title,
                background: 'rgba(30, 41, 59, 0.9)',
                color: '#f8fafc',
                customClass: {
                    popup: 'border border-gray-700 backdrop-blur-md'
                }
            });
        };

        @if(session('status'))
            showToast("{{ session('status') }}");
        @endif
        
        @if($errors->any())
            showToast("{{ $errors->first() }}", 'error');
        @endif
    </script>
    
    @stack('scripts')
</body>
</html>
