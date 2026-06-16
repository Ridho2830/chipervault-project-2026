<?php

namespace App\Services;

use Exception;

class EncryptionService
{
    /**
     * Melakukan enkripsi data plaintext menggunakan algoritma AES-256-GCM.
     * 
     * @param string $plaintext Data mentah yang akan dienkripsi
     * @param string $password Kata sandi yang digunakan sebagai dasar enkripsi
     * @return array Berisi ciphertext, iv (Initialization Vector), dan salt yang sudah di-encode dalam bentuk Base64
     */
    public static function encrypt(string $plaintext, string $password): array
    {
        // 1. Generate salt acak berukuran 32-byte untuk memperkuat pembuatan kunci
        $salt = random_bytes(32);

        // 2. Turunkan kunci sepanjang 32-byte menggunakan metode PBKDF2 
        //    (dengan algoritma HMAC-SHA256, dan diulang sebanyak 310.000 iterasi untuk mencegah brute-force)
        $key = hash_pbkdf2('sha256', $password, $salt, 310000, 32, true);

        // 3. Generate Initialization Vector (IV) acak berukuran 12-byte yang direkomendasikan untuk mode GCM
        $iv = random_bytes(12);

        // 4. Lakukan proses enkripsi menggunakan AES-256-GCM
        $tag = '';
        $ciphertext = openssl_encrypt(
            $plaintext, 
            'aes-256-gcm', 
            $key, 
            OPENSSL_RAW_DATA, 
            $iv, 
            $tag
        );

        if ($ciphertext === false) {
            throw new Exception("Encryption failed.");
        }

        // 5. Gabungkan 16-byte authentication tag (GCM tag) ke bagian akhir ciphertext 
        //    agar mudah disimpan dalam satu string di database
        $ciphertextWithTag = $ciphertext . $tag;

        // 6. Kembalikan data dalam format Base64 agar aman dikirim dan disimpan sebagai teks
        return [
            'ciphertext' => base64_encode($ciphertextWithTag),
            'iv' => base64_encode($iv),
            'salt' => base64_encode($salt)
        ];
    }

    /**
     * Melakukan dekripsi data ciphertext kembali ke bentuk semula menggunakan AES-256-GCM.
     * 
     * @param string $ciphertextBase64 Ciphertext dalam bentuk Base64
     * @param string $password Kata sandi untuk dekripsi
     * @param string $ivBase64 Initialization Vector dalam bentuk Base64
     * @param string $saltBase64 Salt dalam bentuk Base64
     * @return string Data plaintext asli yang telah berhasil didekripsi
     * @throws Exception Jika dekripsi gagal atau autentikasi data tidak cocok (password salah atau data korup)
     */
    public static function decrypt(string $ciphertextBase64, string $password, string $ivBase64, string $saltBase64): string
    {
        // 1. Decode kembali input yang berformat Base64 ke dalam bentuk biner aslinya
        $ciphertextWithTag = base64_decode($ciphertextBase64);
        $iv = base64_decode($ivBase64);
        $salt = base64_decode($saltBase64);

        if ($ciphertextWithTag === false || $iv === false || $salt === false) {
            throw new Exception("Invalid Base64 encoding.");
        }

        // 2. Pisahkan 16-byte GCM tag yang sebelumnya digabungkan di akhir ciphertext
        if (strlen($ciphertextWithTag) < 16) {
            throw new Exception("Ciphertext is too short to contain a valid tag.");
        }
        
        $tag = substr($ciphertextWithTag, -16);
        $ciphertext = substr($ciphertextWithTag, 0, -16);

        // 3. Turunkan kunci dekripsi dengan menggunakan parameter PBKDF2 yang sama persis saat enkripsi
        $key = hash_pbkdf2('sha256', $password, $salt, 310000, 32, true);

        // 4. Lakukan proses dekripsi. Fungsi ini akan secara otomatis memverifikasi tag GCM
        $plaintext = openssl_decrypt(
            $ciphertext, 
            'aes-256-gcm', 
            $key, 
            OPENSSL_RAW_DATA, 
            $iv, 
            $tag
        );

        if ($plaintext === false) {
            throw new Exception("Decryption failed. Incorrect password or corrupted data.");
        }

        return $plaintext;
    }
}
