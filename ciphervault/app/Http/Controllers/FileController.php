<?php

namespace App\Http\Controllers;

use App\Models\EncryptedFile;
use App\Models\ActivityLog;
use App\Services\EncryptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    /**
     * Menampilkan halaman daftar file terenkripsi milik pengguna.
     */
    public function index()
    {
        $files = Auth::user()->encryptedFiles()->latest()->get();
        return view('files.index', compact('files'));
    }

    /**
     * Menangani proses penyimpanan data file yang baru dienkripsi.
     */
    public function store(Request $request)
    {
        // 1. Validasi input: pastikan ada file yang diunggah dan password yang diberikan
        $request->validate([
            'file' => 'required|file',
            'password' => 'required|string',
        ]);

        // 2. Ambil data file mentah (plaintext) dari request
        $uploadedFile = $request->file('file');
        $fileContent = $uploadedFile->get();

        // 3. Proses enkripsi file mentah menggunakan password di sisi server
        try {
            $encryptedData = EncryptionService::encrypt($fileContent, $request->password);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Encryption failed'], 500);
        }

        // 4. Simpan metadata dan hasil enkripsi (ciphertext, iv, salt) ke database
        $file = Auth::user()->encryptedFiles()->create([
            'original_name' => $uploadedFile->getClientOriginalName(),
            'encrypted_name' => 'enc_' . time() . '_' . $uploadedFile->getClientOriginalName(),
            'file_size' => $uploadedFile->getSize(),
            'mime_type' => $uploadedFile->getClientMimeType() ?: 'application/octet-stream',
            'ciphertext' => $encryptedData['ciphertext'],
            'iv' => $encryptedData['iv'],
            'salt' => $encryptedData['salt'],
        ]);

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
     */
    public function download(EncryptedFile $file, Request $request)
    {
        // 1. Pastikan file ini milik user yang sedang login
        if ($file->user_id !== Auth::id()) {
            abort(403);
        }

        // 2. Validasi input password yang dikirim via POST
        $request->validate([
            'password' => 'required|string',
        ]);

        // 3. Proses dekripsi ciphertext dari database menggunakan password yang diberikan
        try {
            $plaintext = EncryptionService::decrypt(
                $file->ciphertext,
                $request->password,
                $file->iv,
                $file->salt
            );
        } catch (\Exception $e) {
            // Jika gagal (password salah / data korup), kembalikan error 400
            return response()->json(['success' => false, 'message' => 'Decryption failed. Incorrect password?'], 400);
        }

        // 4. Catat aktivitas dekripsi file ke dalam log (Audit Trail)
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'decrypt_file',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // 5. Kembalikan data mentah hasil dekripsi dalam format Base64 (untuk di-handle frontend)
        return response()->json([
            'success' => true,
            'file_content' => base64_encode($plaintext),
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
