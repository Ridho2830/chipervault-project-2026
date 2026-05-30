<?php

namespace App\Http\Controllers;

use App\Models\SecureNote;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * Menampilkan halaman daftar catatan aman (secure notes) milik pengguna.
     * Lokasi fitur: Tampilan daftar catatan (notes/index.blade.php)
     */
    public function index()
    {
        $notes = Auth::user()->secureNotes()->latest()->get();
        return view('notes.index', compact('notes'));
    }

    /**
     * Menangani proses penyimpanan catatan baru yang telah dienkripsi.
     * Menerima ciphertext dari klien (frontend) dan menyimpannya ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'ciphertext' => 'required|string',
            'iv' => 'required|string',
            'salt' => 'required|string',
        ]);

        $note = Auth::user()->secureNotes()->create($request->all());

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
     * Mengembalikan ciphertext ke klien (frontend) untuk dibuka secara lokal.
     */
    public function show(SecureNote $note, Request $request)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'decrypt_note',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        return response()->json([
            'ciphertext' => $note->ciphertext,
            'iv' => $note->iv,
            'salt' => $note->salt,
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
