<?php

namespace App\Http\Controllers;

use App\Models\EncryptedFile;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    /**
     * Menampilkan halaman daftar file terenkripsi milik pengguna.
     * Lokasi fitur: Tampilan daftar file (files/index.blade.php)
     */
    public function index()
    {
        $files = Auth::user()->encryptedFiles()->latest()->get();
        return view('files.index', compact('files'));
    }

    /**
     * Menangani proses penyimpanan data file yang baru dienkripsi.
     * Menerima ciphertext dan informasi file dari klien (frontend) ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'original_name' => 'required|string',
            'encrypted_name' => 'required|string',
            'file_size' => 'required|integer',
            'mime_type' => 'required|string',
            'ciphertext' => 'required|string',
            'iv' => 'required|string',
            'salt' => 'required|string',
        ]);

        $file = Auth::user()->encryptedFiles()->create($request->all());

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'encrypt_file',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json(['success' => true, 'file' => $file]);
    }

    /**
     * Menangani proses pengambilan data file untuk diunduh (didekripsi).
     * Mengembalikan ciphertext dan kunci deskripsi ke klien (frontend).
     */
    public function download(EncryptedFile $file, Request $request)
    {
        if ($file->user_id !== Auth::id()) {
            abort(403);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'decrypt_file',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'ciphertext' => $file->ciphertext,
            'iv' => $file->iv,
            'salt' => $file->salt,
            'mime_type' => $file->mime_type,
            'original_name' => $file->original_name
        ]);
    }
    
    /**
     * Menangani proses penghapusan data file terenkripsi dari database.
     */
    public function destroy(EncryptedFile $file, Request $request)
    {
        if ($file->user_id !== Auth::id()) {
            abort(403);
        }
        
        $file->delete();
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete_file',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return redirect()->back()->with('status', 'File deleted successfully.');
    }
}
