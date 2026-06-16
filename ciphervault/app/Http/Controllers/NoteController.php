<?php

namespace App\Http\Controllers;

use App\Models\SecureNote;
use App\Models\ActivityLog;
use App\Services\EncryptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Menampilkan halaman daftar catatan aman (secure notes) milik pengguna.
     */
    public function index()
    {
        $notes = Auth::user()->secureNotes()->latest()->get();
        return view('notes.index', compact('notes'));
    }

    /**
     * Menangani proses penyimpanan catatan baru yang telah dienkripsi.
     */
    public function store(Request $request)
    {
        // 1. Validasi input: wajib ada judul, isi catatan (plaintext), dan password
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Proses enkripsi isi catatan di sisi server
        try {
            $encryptedData = EncryptionService::encrypt($request->content, $request->password);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Encryption failed'], 500);
        }

        // 3. Simpan judul (plaintext) dan hasil enkripsi konten ke database
        $note = Auth::user()->secureNotes()->create([
            'title' => $request->title,
            'ciphertext' => $encryptedData['ciphertext'],
            'iv' => $encryptedData['iv'],
            'salt' => $encryptedData['salt']
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'encrypt_note',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json(['success' => true, 'note' => $note]);
    }

    /**
     * Menangani proses pengambilan data spesifik untuk satu catatan (untuk didekripsi).
     */
    public function decrypt(SecureNote $note, Request $request)
    {
        // 1. Pastikan catatan ini milik user yang sedang login
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        // 2. Validasi password yang dikirim via POST
        $request->validate([
            'password' => 'required|string',
        ]);

        // 3. Lakukan dekripsi isi catatan dari database
        try {
            $plaintext = EncryptionService::decrypt(
                $note->ciphertext,
                $request->password,
                $note->iv,
                $note->salt
            );
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Decryption failed. Incorrect password?'], 400);
        }

        // 4. Catat aktivitas pembacaan catatan rahasia ke log
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'decrypt_note',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // 5. Kembalikan isi catatan yang sudah berupa plaintext
        return response()->json([
            'success' => true,
            'content' => $plaintext
        ]);
    }

    /**
     * Menangani proses penghapusan catatan aman dari database.
     */
    public function destroy(SecureNote $note, Request $request)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }
        
        $note->delete();
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete_note',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        return redirect()->back()->with('status', 'Note deleted successfully.');
    }
}
