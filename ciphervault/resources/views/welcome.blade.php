@extends('layouts.app')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[80vh] text-center relative">
    <!-- Glowing background effect -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-blue-600/20 rounded-full blur-[120px] -z-10 pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/4 -translate-y-1/3 w-[400px] h-[400px] bg-emerald-600/20 rounded-full blur-[100px] -z-10 pointer-events-none"></div>

    <div class="mb-6 inline-flex items-center gap-2 px-4 py-2 rounded-full glass-panel border-blue-500/30 text-blue-300 text-sm font-medium animate-pulse">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        Military-Grade AES-256-GCM Encryption
    </div>

    <h1 class="text-5xl md:text-7xl font-extrabold mb-6 tracking-tight">
        <span class="block text-white">Your Data.</span>
        <span class="block bg-clip-text text-transparent bg-gradient-to-r from-blue-400 via-indigo-400 to-emerald-400 pb-2">Zero Knowledge.</span>
    </h1>
    
    <p class="text-lg md:text-xl text-gray-400 max-w-2xl mb-10 leading-relaxed">
        CipherVault encrypts your files and notes directly in your browser. We never see your password, and we never see your data. True end-to-end security.
    </p>

    <div class="flex flex-col sm:flex-row gap-4 w-full sm:w-auto">
        <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white transition-all duration-200 bg-gradient-to-r from-blue-500 to-indigo-600 border border-transparent rounded-2xl hover:from-blue-400 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-600 shadow-[0_0_40px_rgba(59,130,246,0.4)] hover:shadow-[0_0_60px_rgba(59,130,246,0.6)] hover:-translate-y-1">
            Create Free Vault
            <svg class="w-5 h-5 ml-2 transition-transform duration-200 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
        </a>
        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-gray-300 transition-all duration-200 bg-slate-800/50 border border-slate-700 rounded-2xl hover:bg-slate-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-700 backdrop-blur-md hover:-translate-y-1 hover:border-slate-600 hover:shadow-xl">
            Access Vault
        </a>
    </div>

    <!-- Feature grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-24 text-left w-full max-w-5xl">
        <div class="glass-panel p-8 rounded-3xl relative overflow-hidden group hover:-translate-y-2 transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/10">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-blue-400 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
            <div class="w-12 h-12 bg-blue-500/20 rounded-2xl flex items-center justify-center mb-6 text-blue-400 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">Client-Side Encryption</h3>
            <p class="text-gray-400 text-sm leading-relaxed">Your data is encrypted using Web Crypto API before it ever leaves your device. The server only stores ciphertext.</p>
        </div>

        <div class="glass-panel p-8 rounded-3xl relative overflow-hidden group hover:-translate-y-2 transition-all duration-300 hover:shadow-2xl hover:shadow-emerald-500/10">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-emerald-400 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
            <div class="w-12 h-12 bg-emerald-500/20 rounded-2xl flex items-center justify-center mb-6 text-emerald-400 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">Secure File Storage</h3>
            <p class="text-gray-400 text-sm leading-relaxed">Upload and store your sensitive documents securely. File contents are encrypted and decrypted on the fly.</p>
        </div>

        <div class="glass-panel p-8 rounded-3xl relative overflow-hidden group hover:-translate-y-2 transition-all duration-300 hover:shadow-2xl hover:shadow-indigo-500/10">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-indigo-500 to-indigo-400 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-500"></div>
            <div class="w-12 h-12 bg-indigo-500/20 rounded-2xl flex items-center justify-center mb-6 text-indigo-400 group-hover:scale-110 transition-transform duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">Private Notes</h3>
            <p class="text-gray-400 text-sm leading-relaxed">Store passwords, seed phrases, and private notes. Decryption requires your master password locally.</p>
        </div>
    </div>
</div>
@endsection
