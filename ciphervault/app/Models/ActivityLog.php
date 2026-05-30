<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $guarded = [];

    /**
     * Mendefinisikan atribut yang akan di-cast secara otomatis.
     * Menggunakan 'encrypted' untuk mengenkripsi kolom log secara otomatis 
     * dengan algoritma AES-256 (bawaan Laravel) sebelum disimpan ke DB.
     */
    protected function casts(): array
    {
        return [
            'action' => 'encrypted',
            'ip_address' => 'encrypted',
            'user_agent' => 'encrypted',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
