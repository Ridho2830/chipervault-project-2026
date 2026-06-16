# CipherVault - Penjelasan Proyek

## Deskripsi Umum
**CipherVault** adalah sebuah aplikasi web brankas digital (secure vault) yang dibangun menggunakan framework **Laravel** (PHP) untuk backend, serta **Tailwind CSS** dan **Vite** untuk frontend. Aplikasi ini dirancang untuk menyimpan file dan catatan (notes) rahasia secara aman dengan menerapkan konsep kriptografi.

## Arsitektur Keamanan (Zero-Knowledge / End-to-End Encryption)
Berdasarkan struktur *controller* yang ada (seperti `FileController.php` dan `NoteController.php`), proyek ini menerapkan **Enkripsi Sisi Klien (Client-Side Encryption)**.
- Saat pengguna mengunggah file atau membuat catatan, proses enkripsi dilakukan di browser (frontend) sebelum dikirim ke server.
- Server (backend Laravel) tidak pernah menerima data asli (plaintext). Server hanya menerima dan menyimpan **`ciphertext`**, **`iv` (Initialization Vector)**, dan **`salt`**.
- Saat pengguna ingin mengunduh file atau melihat catatan, server mengirimkan kembali `ciphertext`, `iv`, dan `salt` tersebut ke browser untuk kemudian didekripsi secara lokal oleh pengguna.
- Pendekatan ini memastikan bahwa pengelola server/database sekalipun tidak dapat melihat isi file atau catatan pengguna (Zero-Knowledge Architecture).

## Fitur Utama

1. **Autentikasi & Otorisasi Pengguna**
   - Sistem login dan registrasi. Data enkripsi (file dan catatan) terikat langsung secara ketat dengan `user_id` masing-masing pengguna sehingga tidak dapat diakses oleh pengguna lain.

2. **Manajemen File Terenkripsi (Encrypted Files)**
   - Pengguna dapat mengamankan file mereka.
   - File yang dienkripsi akan disimpan informasi metadatanya di database (seperti nama asli file, tipe MIME, dan ukuran) bersamaan dengan data kriptografinya (`ciphertext`, `iv`, `salt`).
   - Fitur hapus dan unduh (dekripsi).

3. **Catatan Aman (Secure Notes)**
   - Sama seperti manajemen file, pengguna dapat menulis teks rahasia.
   - Teks ini dienkripsi secara lokal dan hanya ciphertext-nya yang disimpan di database.

4. **Pencatatan Aktivitas (Activity Logging)**
   - Aplikasi memiliki fitur audit trail (`ActivityLog`).
   - Setiap tindakan penting yang dilakukan pengguna seperti mengenkripsi, mendekripsi, dan menghapus data akan dicatat.
   - Data yang dicatat meliputi jenis aktivitas, alamat IP (`ip_address`), dan *user agent* browser yang digunakan.

## Teknologi yang Digunakan
- **Backend:** Laravel (PHP 8.2+), Eloquent ORM.
- **Frontend:** Blade Templates, Tailwind CSS, Vite. Proses kriptografi (enkripsi/dekripsi) kemungkinan menggunakan Web Crypto API atau library JS seperti CryptoJS.
- **Database:** Relasional (MySQL/SQLite/PostgreSQL) dengan migration yang mencakup tabel `users`, `encrypted_files`, `secure_notes`, dan `activity_logs`.

## Kesimpulan
Proyek ini sangat relevan dan bagus untuk proyek mata kuliah kriptografi/keamanan data. Implementasi pemindahan beban enkripsi ke sisi klien adalah praktik keamanan modern yang memastikan privasi tingkat tinggi, sehingga aplikasi hanya bertindak sebagai penyimpan data acak yang tidak bermakna tanpa kunci dekripsi dari pengguna.
