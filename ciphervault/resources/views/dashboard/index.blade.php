@extends('layouts.app')

@section('content')
<div class="mb-10 relative">
    <!-- Decorative background glow -->
    <div class="absolute -top-10 -left-10 w-64 h-64 bg-blue-500/10 rounded-full blur-[80px] -z-10 pointer-events-none"></div>

    <h1 class="text-4xl font-extrabold text-white mb-3 tracking-tight">Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-emerald-400">{{ Auth::user()->name }}</span></h1>
    <p class="text-gray-400 flex items-center gap-2">
        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
        Your digital vault is secure. All encryption happens locally in your browser.
    </p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <a href="{{ route('files.index') }}" class="glass-panel p-8 rounded-3xl relative overflow-hidden group hover:-translate-y-1 transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/10 block">
        <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:opacity-20 transition-opacity duration-300 transform group-hover:scale-110">
            <svg class="w-24 h-24 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v5H4a2 2 0 01-2-2V6zm14 3H8a1 1 0 00-1 1v5a1 1 0 001 1h10a1 1 0 001-1v-5a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        </div>
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-400 mb-1 group-hover:text-blue-400 transition-colors">Encrypted Files</h3>
                <p class="text-5xl font-extrabold text-white mt-2">{{ $filesCount }}</p>
            </div>
            <div class="p-5 bg-blue-500/10 rounded-2xl text-blue-400 border border-blue-500/20 shadow-[0_0_15px_rgba(59,130,246,0.15)] group-hover:shadow-[0_0_25px_rgba(59,130,246,0.3)] transition-all">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>
    </a>
    
    <a href="{{ route('notes.index') }}" class="glass-panel p-8 rounded-3xl relative overflow-hidden group hover:-translate-y-1 transition-all duration-300 hover:shadow-2xl hover:shadow-emerald-500/10 block">
        <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:opacity-20 transition-opacity duration-300 transform group-hover:scale-110">
            <svg class="w-24 h-24 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
        </div>
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-400 mb-1 group-hover:text-emerald-400 transition-colors">Secure Notes</h3>
                <p class="text-5xl font-extrabold text-white mt-2">{{ $notesCount }}</p>
            </div>
            <div class="p-5 bg-emerald-500/10 rounded-2xl text-emerald-400 border border-emerald-500/20 shadow-[0_0_15px_rgba(16,185,129,0.15)] group-hover:shadow-[0_0_25px_rgba(16,185,129,0.3)] transition-all">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
        </div>
    </a>
</div>

<div class="glass-panel rounded-3xl overflow-hidden shadow-2xl">
    <div class="px-8 py-6 border-b border-slate-700/50 flex items-center justify-between bg-slate-800/30">
        <h3 class="text-xl font-bold text-white flex items-center gap-3">
            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Recent Activity
        </h3>
    </div>
    
    @if($recentActivities->isEmpty())
        <div class="p-16 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-800 mb-4">
                <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <p class="text-gray-400 text-lg">No recent activity found.</p>
            <p class="text-gray-500 text-sm mt-2">Upload a file or create a note to see activity here.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-slate-900/50">
                    <tr>
                        <th scope="col" class="px-8 py-4 font-semibold">Action</th>
                        <th scope="col" class="px-8 py-4 font-semibold">Date</th>
                        <th scope="col" class="px-8 py-4 font-semibold text-right">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @foreach($recentActivities as $activity)
                    <tr class="hover:bg-slate-800/40 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                @php
                                    $iconBg = 'bg-slate-800';
                                    $iconColor = 'text-slate-400';
                                    if(Str::contains($activity->action, 'encrypt')) { $iconBg = 'bg-emerald-500/10'; $iconColor = 'text-emerald-400'; }
                                    if(Str::contains($activity->action, 'decrypt')) { $iconBg = 'bg-blue-500/10'; $iconColor = 'text-blue-400'; }
                                    if(Str::contains($activity->action, 'delete')) { $iconBg = 'bg-rose-500/10'; $iconColor = 'text-rose-400'; }
                                @endphp
                                <div class="p-2 rounded-lg {{ $iconBg }} {{ $iconColor }}">
                                    @if(Str::contains($activity->action, 'file'))
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                    @elseif(Str::contains($activity->action, 'note'))
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    @endif
                                </div>
                                <span class="font-medium text-gray-300 group-hover:text-white transition-colors">
                                    {{ str_replace('_', ' ', Str::title($activity->action)) }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-gray-400">
                            {{ $activity->created_at->diffForHumans() }}
                        </td>
                        <td class="px-8 py-5 font-mono text-xs text-gray-500 text-right">
                            <span class="px-2 py-1 bg-slate-900 rounded-md border border-slate-800">{{ $activity->ip_address }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
