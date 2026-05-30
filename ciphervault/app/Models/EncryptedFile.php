<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EncryptedFile extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Mendefinisikan atribut yang akan di-cast secara otomatis.
     * Menggunakan 'encrypted' untuk mengenkripsi kolom secara otomatis 
     * dengan algoritma AES-256 (bawaan Laravel) sebelum disimpan ke DB.
     */
    protected function casts(): array
    {
        return [
            'original_name' => 'encrypted',
            'encrypted_name' => 'encrypted',
            'mime_type' => 'encrypted',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
