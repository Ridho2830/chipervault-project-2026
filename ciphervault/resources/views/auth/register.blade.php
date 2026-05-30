@extends('layouts.app')

@section('content')
<div class="flex-grow flex items-center justify-center relative min-h-[75vh]">
    <!-- Background glowing orbs -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-indigo-600/20 rounded-full blur-[100px] -z-10 pointer-events-none"></div>

    <div class="w-full max-w-md">
        <div class="glass-panel p-8 sm:p-10 rounded-3xl shadow-2xl relative overflow-hidden">
            <!-- Top highlight line -->
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-indigo-500 to-emerald-400"></div>
            
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-500/10 mb-4 border border-indigo-500/20">
                    <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <h2 class="text-3xl font-bold text-white tracking-tight">Create Vault</h2>
                <p class="text-gray-400 mt-2 text-sm">Set up your secure end-to-end encrypted storage.</p>
            </div>
            
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf
                
                <div class="relative group">
                    <label for="name" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-indigo-400 transition-colors">Full Name</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500 group-focus-within:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                            class="block w-full pl-11 pr-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none" placeholder="John Doe">
                    </div>
                </div>

                <div class="relative group">
                    <label for="email" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-indigo-400 transition-colors">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500 group-focus-within:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                            class="block w-full pl-11 pr-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none" placeholder="you@example.com">
                    </div>
                </div>

                <div class="relative group">
                    <label for="password" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-indigo-400 transition-colors">Master Password</label>
                    <div class="relative mb-2">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500 group-focus-within:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" name="password" id="password" required
                            class="block w-full pl-11 pr-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none" placeholder="••••••••">
                    </div>
                    
                    <div class="w-full bg-slate-800/50 rounded-full h-1.5 overflow-hidden border border-slate-700/50">
                        <div id="password-strength-bar" class="bg-indigo-500 h-1.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <p id="password-strength-text" class="text-[10px] uppercase font-bold tracking-wider mt-1.5 text-gray-500 text-right h-4"></p>
                </div>

                <div class="relative group">
                    <label for="password_confirmation" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 group-focus-within:text-indigo-400 transition-colors">Confirm Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-500 group-focus-within:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="block w-full pl-11 pr-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none" placeholder="••••••••">
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-blue-500/10 border border-blue-500/20 text-xs text-blue-200 mt-4 leading-relaxed">
                    <strong class="text-blue-400 block mb-1">Important:</strong>
                    Your Master Password is used to encrypt your data locally. We do not store it and cannot recover it if you forget it.
                </div>

                <button type="submit" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-[0_0_20px_rgba(99,102,241,0.25)] text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-slate-900 transition-all transform hover:-translate-y-0.5 mt-6">
                    Create Secure Vault
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>
            
            <p class="mt-8 text-center text-sm text-gray-400">
                Already have a vault? <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold transition-colors">Sign in</a>
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if(window.CipherVault) {
            window.CipherVault.updateStrengthBar('password', 'password-strength-bar', 'password-strength-text');
        } else {
            setTimeout(() => {
                if(window.CipherVault) window.CipherVault.updateStrengthBar('password', 'password-strength-bar', 'password-strength-text');
            }, 500);
        }
    });
</script>
@endpush
