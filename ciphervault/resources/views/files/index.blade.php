@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center gap-3">
            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Secure Files
        </h1>
        <p class="text-gray-400 mt-1">End-to-end encrypted file storage.</p>
    </div>
    <button onclick="document.getElementById('uploadModal').classList.remove('hidden')" class="group flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white px-6 py-3 rounded-xl font-semibold shadow-[0_0_20px_rgba(59,130,246,0.3)] hover:shadow-[0_0_25px_rgba(59,130,246,0.5)] transition-all transform hover:-translate-y-0.5">
        <svg class="w-5 h-5 group-hover:animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
        Encrypt & Upload File
    </button>
</div>

<div class="glass-panel rounded-3xl overflow-hidden shadow-2xl relative">
    @if($files->isEmpty())
        <div class="p-20 text-center flex flex-col items-center">
            <div class="w-24 h-24 bg-blue-500/10 rounded-full flex items-center justify-center mb-6 border border-blue-500/20">
                <svg class="w-12 h-12 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Your Vault is Empty</h3>
            <p class="text-gray-400 max-w-md mx-auto">Upload documents, images, or any sensitive files. They will be encrypted on the server.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-400 uppercase bg-slate-900/50">
                    <tr>
                        <th scope="col" class="px-8 py-5 font-semibold tracking-wider">File Name</th>
                        <th scope="col" class="px-8 py-5 font-semibold tracking-wider">Size</th>
                        <th scope="col" class="px-8 py-5 font-semibold tracking-wider">Encrypted At</th>
                        <th scope="col" class="px-8 py-5 font-semibold tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @foreach($files as $file)
                    <tr class="hover:bg-slate-800/40 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-blue-500/10 text-blue-400 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="font-medium text-gray-200 group-hover:text-white transition-colors">
                                    {{ $file->original_name }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-gray-400">
                            {{ number_format($file->file_size / 1024, 2) }} KB
                        </td>
                        <td class="px-8 py-5 text-gray-400">
                            {{ $file->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-8 py-5 text-right flex justify-end gap-3">
                            <button onclick="downloadFile({{ $file->id }}, '{{ addslashes($file->original_name) }}')" class="flex items-center gap-2 text-emerald-400 hover:text-white bg-emerald-500/10 hover:bg-emerald-500 border border-emerald-500/20 hover:border-emerald-500 px-4 py-2 rounded-lg transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                Decrypt
                            </button>
                            <form action="{{ route('files.destroy', $file) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this file? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex items-center justify-center p-2 text-rose-400 hover:text-white bg-rose-500/10 hover:bg-rose-500 border border-rose-500/20 hover:border-rose-500 rounded-lg transition-all duration-200 tooltip" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="inline-block align-bottom glass-panel rounded-3xl text-left overflow-hidden shadow-2xl border border-slate-600/50 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 to-indigo-500"></div>
            
            <div class="px-8 pt-8 pb-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-blue-500/10 rounded-full flex items-center justify-center border border-blue-500/20 text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white" id="modal-title">Encrypt & Upload</h3>
                        <p class="text-xs text-blue-400">Server-Side AES-256-GCM</p>
                    </div>
                </div>
                
                <div class="space-y-5">
                    <div class="relative group">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Select File</label>
                        <input type="file" id="fileInput" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-500 bg-slate-900/50 border border-slate-700 rounded-xl p-1.5 outline-none focus:border-blue-500 transition-colors cursor-pointer">
                    </div>
                    <div class="relative group">
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Encryption Password</label>
                        <input type="password" id="encPassword" class="block w-full px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 transition-all outline-none" placeholder="Enter a strong password">
                        <div class="mt-2 w-full bg-slate-800/50 rounded-full h-1.5 overflow-hidden border border-slate-700/50">
                            <div id="enc-password-bar" class="bg-blue-500 h-1.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <p id="enc-password-text" class="text-[10px] uppercase font-bold tracking-wider mt-1.5 text-gray-500 text-right h-4"></p>
                    </div>
                    <div class="p-3 bg-rose-500/10 border border-rose-500/20 rounded-lg text-xs text-rose-200">
                        <strong>Warning:</strong> If you lose this password, the file cannot be decrypted.
                    </div>
                </div>
            </div>
            <div class="px-8 py-5 bg-slate-800/40 border-t border-slate-700/50 flex flex-row-reverse gap-3">
                <button type="button" id="uploadBtn" onclick="processUpload()" class="inline-flex justify-center rounded-xl border border-transparent shadow-[0_0_15px_rgba(37,99,235,0.3)] px-6 py-2.5 bg-blue-600 text-sm font-semibold text-white hover:bg-blue-500 focus:outline-none transition-all transform hover:-translate-y-0.5">
                    Encrypt & Upload
                </button>
                <button type="button" onclick="document.getElementById('uploadModal').classList.add('hidden')" class="inline-flex justify-center rounded-xl border border-slate-600 px-6 py-2.5 bg-slate-800/50 text-sm font-semibold text-gray-300 hover:bg-slate-700 hover:text-white focus:outline-none transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Password Prompt Modal for Decryption -->
<div id="decryptModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/60 backdrop-blur-sm" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="inline-block align-bottom glass-panel rounded-3xl text-left overflow-hidden shadow-2xl border border-slate-600/50 transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-500 to-emerald-400"></div>
            
            <div class="px-8 pt-8 pb-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-emerald-500/10 rounded-full flex items-center justify-center border border-emerald-500/20 text-emerald-400 animate-pulse">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Decrypt File</h3>
                        <p class="text-xs text-emerald-400">Server Decryption Process</p>
                    </div>
                </div>
                
                <div id="decryptPasswordSection">
                    <p class="text-sm text-gray-400 mb-4">Enter the password used to encrypt <br><span id="decryptFileName" class="font-bold text-white block mt-1 truncate"></span></p>
                    
                    <div class="relative">
                        <input type="password" id="decPassword" class="block w-full px-4 py-3 bg-slate-900/50 border border-slate-700 rounded-xl text-white placeholder-gray-500 focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 transition-all outline-none" placeholder="Master Password">
                    </div>
                    <input type="hidden" id="decFileId">
                </div>

                <div id="decryptResultSection" class="hidden mt-2 text-center">
                    <div class="p-6 bg-slate-900/80 rounded-2xl border border-slate-700/50 mb-6 flex flex-col items-center">
                        <svg class="w-12 h-12 text-emerald-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h4 class="text-lg font-bold text-white">File Decrypted Successfully</h4>
                        <p class="text-sm text-gray-400 mt-1 mb-4">Your file was decrypted on the server and is ready.</p>
                        
                        <!-- Preview Container -->
                        <div id="filePreviewContainer" class="w-full max-h-64 overflow-y-auto rounded-lg border border-slate-700 bg-slate-950 p-2 mb-4 hidden relative">
                            <!-- Content injected via JS -->
                        </div>

                    </div>
                    <a id="downloadLinkBtn" href="#" download class="inline-flex justify-center items-center gap-2 rounded-xl shadow-[0_0_15px_rgba(16,185,129,0.3)] px-6 py-3 bg-emerald-600 text-sm font-semibold text-white hover:bg-emerald-500 transition-all transform hover:-translate-y-0.5 w-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download File
                    </a>
                </div>
            </div>
            
            <div class="px-8 py-5 bg-slate-800/40 border-t border-slate-700/50 flex flex-row-reverse gap-3">
                <button type="button" id="decryptBtn" onclick="processDecryption()" class="inline-flex justify-center rounded-xl border border-transparent shadow-[0_0_15px_rgba(16,185,129,0.3)] px-6 py-2.5 bg-emerald-600 text-sm font-semibold text-white hover:bg-emerald-500 focus:outline-none transition-all transform hover:-translate-y-0.5">
                    Decrypt
                </button>
                <button type="button" onclick="closeDecryptModal()" class="inline-flex justify-center rounded-xl border border-slate-600 px-6 py-2.5 bg-slate-800/50 text-sm font-semibold text-gray-300 hover:bg-slate-700 hover:text-white focus:outline-none transition-colors">
                    Close
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
            window.CipherVault.updateStrengthBar('encPassword', 'enc-password-bar', 'enc-password-text');
        } else {
            setTimeout(() => {
                if(window.CipherVault) window.CipherVault.updateStrengthBar('encPassword', 'enc-password-bar', 'enc-password-text');
            }, 500);
        }
    });

    async function processUpload() {
        const fileInput = document.getElementById('fileInput');
        const passwordInput = document.getElementById('encPassword');
        
        if (fileInput.files.length === 0) return window.showToast('Please select a file', 'error');
        if (!passwordInput.value) return window.showToast('Please enter an encryption password', 'error');

        const file = fileInput.files[0];
        const btn = document.getElementById('uploadBtn');
        const originalText = btn.innerText;
        btn.innerText = 'Encrypting & Uploading...';
        btn.disabled = true;

        try {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('password', passwordInput.value);

            const headers = { ...window.fetchConfig.headers };
            delete headers['Content-Type'];

            const response = await fetch('{{ route("files.store") }}', {
                method: 'POST',
                headers: headers,
                body: formData
            });

            const data = await response.json();
            
            if (response.ok) {
                window.showToast('File encrypted and uploaded successfully');
                setTimeout(() => window.location.reload(), 1500);
            } else {
                throw new Error(data.message || 'Upload failed');
            }
        } catch (error) {
            console.error(error);
            window.showToast(error.message, 'error');
            btn.innerText = originalText;
            btn.disabled = false;
        }
    }

    let currentObjectURL = null;

    function downloadFile(id, name) {
        document.getElementById('decFileId').value = id;
        document.getElementById('decryptFileName').innerText = name;
        document.getElementById('decPassword').value = '';
        
        // Reset modal UI
        document.getElementById('decryptPasswordSection').classList.remove('hidden');
        document.getElementById('decryptBtn').classList.remove('hidden');
        document.getElementById('decryptResultSection').classList.add('hidden');
        
        // Clear previous blob
        if (currentObjectURL) {
            window.URL.revokeObjectURL(currentObjectURL);
            currentObjectURL = null;
        }
        
        document.getElementById('decryptModal').classList.remove('hidden');
    }

    function closeDecryptModal() {
        document.getElementById('decryptModal').classList.add('hidden');
        if (currentObjectURL) {
            window.URL.revokeObjectURL(currentObjectURL);
            currentObjectURL = null;
        }
    }

    async function processDecryption() {
        const id = document.getElementById('decFileId').value;
        const password = document.getElementById('decPassword').value;
        const name = document.getElementById('decryptFileName').innerText;
        
        if (!password) return window.showToast('Please enter the password', 'error');

        const btn = document.getElementById('decryptBtn');
        const originalText = btn.innerText;
        btn.innerText = 'Decrypting...';
        btn.disabled = true;

        try {
            const response = await fetch(`/files/${id}/download`, {
                method: 'POST',
                headers: window.fetchConfig.headers,
                body: JSON.stringify({ password: password })
            });
            
            const data = await response.json();
            
            if (!response.ok) throw new Error(data.message || 'Failed to decrypt file');
            
            btn.innerText = 'Preparing...';
            
            const byteCharacters = atob(data.file_content);
            const byteNumbers = new Array(byteCharacters.length);
            for (let i = 0; i < byteCharacters.length; i++) {
                byteNumbers[i] = byteCharacters.charCodeAt(i);
            }
            const byteArray = new Uint8Array(byteNumbers);
            const blob = new Blob([byteArray], { type: data.mime_type });
            
            currentObjectURL = window.URL.createObjectURL(blob);
            
            const downloadBtn = document.getElementById('downloadLinkBtn');
            downloadBtn.href = currentObjectURL;
            downloadBtn.download = data.original_name;
            
            // Handle Preview
            const previewContainer = document.getElementById('filePreviewContainer');
            previewContainer.innerHTML = ''; // Clear old preview
            previewContainer.classList.remove('hidden');
            
            if (data.mime_type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = currentObjectURL;
                img.className = 'max-w-full h-auto rounded mx-auto';
                previewContainer.appendChild(img);
            } else if (data.mime_type.startsWith('text/') || data.mime_type === 'application/json') {
                const text = await blob.text();
                const pre = document.createElement('pre');
                pre.className = 'text-left text-xs text-gray-300 whitespace-pre-wrap font-mono';
                pre.textContent = text;
                previewContainer.appendChild(pre);
            } else if (data.mime_type === 'application/pdf') {
                const iframe = document.createElement('iframe');
                iframe.src = currentObjectURL;
                iframe.className = 'w-full h-64 border-0 rounded';
                previewContainer.appendChild(iframe);
            } else {
                const noPreview = document.createElement('div');
                noPreview.className = 'text-sm text-gray-500 p-4';
                noPreview.innerText = 'Preview not available for this file type.';
                previewContainer.appendChild(noPreview);
            }
            
            // Show result view
            document.getElementById('decryptPasswordSection').classList.add('hidden');
            document.getElementById('decryptBtn').classList.add('hidden');
            document.getElementById('decryptResultSection').classList.remove('hidden');
            
            window.showToast('File decrypted successfully');
            
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
