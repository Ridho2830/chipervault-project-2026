<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama (dashboard) pengguna.
     * Mengambil dan menampilkan ringkasan data seperti jumlah file terenkripsi, 
     * catatan aman, dan aktivitas terbaru.
     */
    public function index()
    {
        $user = Auth::user();
        
        $filesCount = $user->encryptedFiles()->count();
        $notesCount = $user->secureNotes()->count();
        $recentActivities = $user->activityLogs()->latest()->take(10)->get();
        
        return view('dashboard.index', compact('filesCount', 'notesCount', 'recentActivities'));
    }
}
