@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center gap-3">
            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Secure Notes
        </h1>
        <p class="text-gray-400 mt-1">End-to-end encrypted private notes.</p>
    </div>
    <button onclick="document.getElementById('createModal').classList.remove('hidden')" class="group flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white px-6 py-3 rounded-xl font-semibold shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:shadow-[0_0_25px_rgba(16,185,129,0.5)] transition-all transform hover:-translate-y-0.5">
        <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Create Secure Note
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($notes as $note)
        <div class="glass-panel p-6 rounded-3xl relative overflow-hidden group hover:-translate-y-1 transition-all duration-300 hover:shadow-2xl hover:shadow-emerald-500/10 flex flex-col h-full">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity duration-300 transform group-hover:scale-110 pointer-events-none">
                <svg class="w-32 h-32 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
            </div>
            
            <div class="flex-grow">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-10 h-10 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-400 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                </div>
                
                <h3 class="text-xl font-bold text-white mb-2 leading-tight group-hover:text-emerald-400 transition-colors">{{ $note->title }}</h3>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-6 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $note->created_at->format('M d, Y') }}
                </p>
            </div>
            
            <div class="flex gap-3 mt-auto relative z-10 pt-4 border-t border-slate-700/50">
                <button onclick="readNote({{ $note->id }}, '{{ addslashes($note->title) }}')" class="flex-1 flex justify-center items-center gap-2 bg-blue-500/10 hover:bg-blue-500 border border-blue-500/20 hover:border-blue-500 text-blue-400 hover:text-white px-4 py-2.5 rounded-xl font-medium transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Decrypt
                </button>
                <form action="{{ route('notes.destroy', $note) }}" method="POST" class="inline" onsubmit="return confirm('Permanently delete this note?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex justify-center items-center w-11 h-11 bg-rose-500/10 hover:bg-rose-500 border border-rose-500/20 hover:border-rose-500 text-rose-400 hover:text-white rounded-xl transition-all duration-200 tooltip" title="Delete">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full p-20 text-center flex flex-col items-center glass-panel rounded-3xl">
            <div class="w-24 h-24 bg-emerald-500/10 rounded-full flex items-center justify-center mb-6 border border-emerald-500/20">
                <svg class="w-12 h-12 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">No Secure Notes</h3>
            <p class="text-gray-400 max-w-md mx-auto">Create your first encrypted note to securely store passwords, private keys, or sensitive information.</p>
        </div>
    @endforelse
</div>

<!-- Create Note Modal -->
<div id="createModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="inline-block align-bottom glass-panel rounded-3xl text-left overflow-hidden shadow-2xl border border-slate-600/50 transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
            
            <div class="px-8 pt-8 pb-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-emerald-500/10 rounded-full flex items-center justify-center border border-emerald-500/20 text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Create Secure Note</h3>
                        <p class="text-xs text-emerald-400">Content encrypted on server</p>
                    </div>
                </div>
                
                <div class="space-y-5">
                    <div class="relative group">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Note Title <span class="text-gray-500 normal-case font-normal">(Stored plaintext)</span></label>
                        <input type="text" id="noteTitle" class="block w-full px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none" placeholder="e.g., Crypto Wallet Recovery Phrase">
                    </div>
                    
                    <div class="relative group">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Note Content <span class="text-emerald-500 normal-case font-normal flex items-center gap-1 inline-flex"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>(Encrypted)</span></label>
                        <textarea id="noteContent" rows="5" class="block w-full px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none resize-none font-mono text-sm" placeholder="Type your secret note here..."></textarea>
                    </div>
                    
                    <div class="relative group">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Encryption Password</label>
                        <input type="password" id="noteEncPassword" class="block w-full px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none" placeholder="Master Password">
                        <div class="mt-2 w-full bg-slate-800/50 rounded-full h-1.5 overflow-hidden border border-slate-700/50">
                            <div id="note-enc-bar" class="bg-emerald-500 h-1.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <p id="note-enc-text" class="text-[10px] uppercase font-bold tracking-wider mt-1.5 text-gray-500 text-right h-4"></p>
                    </div>
                </div>
            </div>
            
            <div class="px-8 py-5 bg-slate-800/40 border-t border-slate-700/50 flex flex-row-reverse gap-3">
                <button type="button" id="createBtn" onclick="processCreate()" class="inline-flex justify-center rounded-xl border border-transparent shadow-[0_0_15px_rgba(16,185,129,0.3)] px-6 py-2.5 bg-emerald-600 text-sm font-semibold text-white hover:bg-emerald-500 focus:outline-none transition-all transform hover:-translate-y-0.5">
                    Encrypt & Save Note
                </button>
                <button type="button" onclick="document.getElementById('createModal').classList.add('hidden')" class="inline-flex justify-center rounded-xl border border-slate-600 px-6 py-2.5 bg-slate-800/50 text-sm font-semibold text-gray-300 hover:bg-slate-700 hover:text-white focus:outline-none transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Read Note Modal -->
<div id="readModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="inline-block align-bottom glass-panel rounded-3xl text-left overflow-hidden shadow-2xl border border-slate-600/50 transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
            
            <div class="px-8 pt-8 pb-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-blue-500/10 rounded-full flex items-center justify-center border border-blue-500/20 text-blue-400 animate-pulse">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white break-words" id="readNoteTitle"></h3>
                        <p class="text-xs text-blue-400">Encrypted Note</p>
                    </div>
                </div>
                
                <div id="readPasswordSection" class="mt-6">
                    <p class="text-sm text-gray-400 mb-4">Enter your encryption password to view this note.</p>
                    <div class="relative">
                        <input type="password" id="readPassword" class="block w-full px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all outline-none" placeholder="Master Password">
                        <input type="hidden" id="readNoteId">
                    </div>
                    <button type="button" id="readBtn" onclick="processRead()" class="mt-6 w-full flex justify-center items-center gap-2 rounded-xl border border-transparent shadow-[0_0_15px_rgba(37,99,235,0.3)] px-6 py-3 bg-blue-600 text-sm font-semibold text-white hover:bg-blue-500 focus:outline-none transition-all transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Decrypt Note
                    </button>
                </div>

                <div id="readContentSection" class="mt-4 hidden">
                    <div class="bg-slate-900/80 p-6 rounded-2xl border border-slate-700/50 shadow-inner relative">
                        <div class="absolute top-3 right-3 text-emerald-400 tooltip" title="Decrypted on server">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <div class="whitespace-pre-wrap text-gray-200 min-h-[150px] font-mono text-sm leading-relaxed" id="decryptedContent"></div>
                    </div>
                </div>
            </div>
            
            <div class="px-8 py-5 bg-slate-800/40 border-t border-slate-700/50 flex flex-row-reverse">
                <button type="button" onclick="closeReadModal()" class="inline-flex justify-center rounded-xl border border-slate-600 px-6 py-2.5 bg-slate-800/50 text-sm font-semibold text-gray-300 hover:bg-slate-700 hover:text-white focus:outline-none transition-colors w-full sm:w-auto">
                    Close Note
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if(window.CipherVault) {
            window.CipherVault.updateStrengthBar('noteEncPassword', 'note-enc-bar', 'note-enc-text');
        } else {
            setTimeout(() => {
                if(window.CipherVault) window.CipherVault.updateStrengthBar('noteEncPassword', 'note-enc-bar', 'note-enc-text');
            }, 500);
        }
    });

    async function processCreate() {
        const title = document.getElementById('noteTitle').value;
        const content = document.getElementById('noteContent').value;
        const password = document.getElementById('noteEncPassword').value;
        
        if (!title) return window.showToast('Please enter a title', 'error');
        if (!content) return window.showToast('Please enter note content', 'error');
        if (!password) return window.showToast('Please enter an encryption password', 'error');

        const btn = document.getElementById('createBtn');
        const originalText = btn.innerText;
        btn.innerText = 'Encrypting on server...';
        btn.disabled = true;

        try {
            const payload = {
                title: title,
                content: content,
                password: password
            };

            const response = await fetch('{{ route("notes.store") }}', {
                method: 'POST',
                headers: window.fetchConfig.headers,
                body: JSON.stringify(payload)
            });

            const data = await response.json();
            
            if (response.ok) {
                window.showToast('Note securely saved');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                throw new Error(data.message || 'Save failed');
            }
        } catch (error) {
            console.error(error);
            window.showToast(error.message, 'error');
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }

    function readNote(id, title) {
        document.getElementById('readNoteId').value = id;
        document.getElementById('readNoteTitle').innerText = title;
        document.getElementById('readPassword').value = '';
        
        document.getElementById('readPasswordSection').classList.remove('hidden');
        document.getElementById('readContentSection').classList.add('hidden');
        document.getElementById('decryptedContent').innerText = '';
        
        document.getElementById('readModal').classList.remove('hidden');
    }

    function closeReadModal() {
        document.getElementById('readModal').classList.add('hidden');
        document.getElementById('decryptedContent').innerText = ''; 
    }

    async function processRead() {
        const id = document.getElementById('readNoteId').value;
        const password = document.getElementById('readPassword').value;
        
        if (!password) return window.showToast('Please enter the password', 'error');

        const btn = document.getElementById('readBtn');
        const originalText = btn.innerText;
        btn.innerText = 'Decrypting...';
        btn.disabled = true;

        try {
            const response = await fetch(`/notes/${id}/decrypt`, {
                method: 'POST',
                headers: window.fetchConfig.headers,
                body: JSON.stringify({ password: password })
            });
            
            const data = await response.json();
            
            if (!response.ok) throw new Error(data.message || 'Failed to decrypt note');
            
            document.getElementById('decryptedContent').innerText = data.content;
            document.getElementById('readPasswordSection').classList.add('hidden');
            document.getElementById('readContentSection').classList.remove('hidden');
            
            window.showToast('Note decrypted successfully');
            
        } catch (error) {
            console.error(error);
            window.showToast(error.message || 'Decryption failed. Incorrect password?', 'error');
        } finally {
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }
</script>
@endpush
