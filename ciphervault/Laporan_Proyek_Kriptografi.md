# LAPORAN PROYEK MATA KULIAH KRIPTOGRAFI
**Judul Proyek:** CipherVault - Aplikasi Penyimpanan Aman dengan Arsitektur Zero-Knowledge
**Mata Kuliah:** Kriptografi (Semester 6)

---

## 1. Pendahuluan
Seiring dengan meningkatnya ancaman keamanan siber, perlindungan terhadap privasi dan kerahasiaan data pengguna menjadi sangat penting. **CipherVault** dikembangkan sebagai solusi brankas digital (*secure vault*) berbasis web yang memungkinkan pengguna untuk mengamankan file dan catatan rahasia mereka. Proyek ini mendemonstrasikan implementasi nyata dari teknik kriptografi modern dengan memindahkan seluruh proses enkripsi dan dekripsi ke sisi klien (browser), sehingga memastikan bahwa data tidak pernah terpapar dalam bentuk *plaintext* kepada server.

## 2. Landasan Teori
### 2.1. AES-256-GCM
AES (Advanced Encryption Standard) dengan mode GCM (*Galois/Counter Mode*) adalah algoritma enkripsi simetris yang tidak hanya menjamin kerahasiaan (*confidentiality*) tetapi juga otentikasi data (*integrity*). Dengan ukuran kunci 256-bit, algoritma ini menawarkan tingkat keamanan kelas militer.
### 2.2. PBKDF2 (Password-Based Key Derivation Function 2)
PBKDF2 digunakan untuk menurunkan kunci kriptografi yang kuat dari sebuah kata sandi (*password*) buatan manusia. Algoritma ini menambahkan *salt* acak dan melakukan ribuan iterasi *hashing* untuk memperlambat serangan *brute-force* atau *dictionary attack*.
### 2.3. Zero-Knowledge Architecture / End-to-End Encryption (E2EE)
Sebuah sistem di mana penyedia layanan (server) tidak memiliki pengetahuan tentang data yang disimpannya karena data tersebut dienkripsi sebelum dikirim ke server, dan server tidak pernah memiliki kunci untuk mendekripsinya.

---

## 3. Arsitektur dan Desain Sistem
Sistem ini memisahkan tanggung jawab antara *frontend* dan *backend*:
- **Frontend (Klien):** Bertanggung jawab atas pengelolaan antarmuka pengguna (Tailwind CSS) dan operasi kriptografi. Memanfaatkan **Web Crypto API** (`window.crypto.subtle`) untuk menjalankan algoritma enkripsi secara langsung di memori browser.
- **Backend (Server):** Dibangun menggunakan **Laravel 12**, berfungsi murni sebagai penyimpan data buta (*blind data store*). Server hanya menangani manajemen pengguna, validasi, *logging*, dan menyimpan metrik serta teks acak (ciphertext).

---

## 4. Implementasi Kriptografi (Detail Teknis)
Penerapan kriptografi pada proyek ini dilakukan di dalam file `encryption.js` menggunakan standar Web Crypto API yang aman dan diakui.

### 4.1. Penurunan Kunci (Key Derivation)
Ketika pengguna memasukkan kata sandi untuk mengenkripsi file, sistem tidak langsung menggunakan kata sandi tersebut sebagai kunci AES. Sebaliknya, sistem menurunkan kunci dengan cara:
1. Meng-generate **32-byte (256-bit) Salt** yang benar-benar acak.
2. Menggunakan **PBKDF2** dengan fungsi *hash* **HMAC-SHA-256**.
3. Melakukan **310.000 iterasi**. Angka iterasi yang tinggi ini sengaja diatur agar komputasi penurunan kunci memakan waktu yang cukup untuk menggagalkan *brute-force*, namun tetap cepat bagi pengguna asli.
4. Menghasilkan **CryptoKey AES-GCM 256-bit**.

### 4.2. Proses Enkripsi
1. File yang diunggah diubah menjadi `ArrayBuffer`.
2. Sistem men-generate **12-byte (96-bit) IV (Initialization Vector)** acak yang disarankan untuk mode GCM.
3. Fungsi `crypto.subtle.encrypt` dipanggil dengan algoritma `AES-GCM`, memasukkan `IV`, `Key` (dari PBKDF2), dan data file.
4. Hasil `ciphertext`, `IV`, dan `Salt` diubah formatnya menjadi **Base64** agar aman dikirim melalui protokol HTTP/JSON ke backend Laravel.

### 4.3. Proses Dekripsi
1. Browser meminta data terenkripsi ke server dan menerima *string* Base64 untuk `ciphertext`, `IV`, dan `Salt`.
2. *String* tersebut dikonversi kembali menjadi `ArrayBuffer`.
3. Pengguna memasukkan kata sandi yang sama. Kunci diturunkan kembali menggunakan **PBKDF2** dengan `Salt` yang dikembalikan oleh server.
4. Fungsi `crypto.subtle.decrypt` dijalankan dengan `Key` dan `IV` yang tepat untuk membongkar `ciphertext` menjadi data asli.
5. Jika dekripsi berhasil (termasuk verifikasi integritas GCM), file disediakan untuk diunduh langsung dari browser. Jika gagal, sistem akan menampilkan *error*.

---

## 5. Fitur Pendukung Keamanan
- **Autentikasi & Otorisasi:** Sistem autentikasi ketat di mana setiap baris data di *database* terikat pada `user_id`. Endpoint Laravel divalidasi agar pengguna hanya bisa mengakses ciphertext miliknya sendiri.
- **Activity Logging (Audit Trail):** Setiap aktivitas sensitif seperti `encrypt_file`, `decrypt_note`, dan percobaan penghapusan dicatat ke dalam tabel `activity_logs` lengkap beserta alamat IP dan agen peramban (*User Agent*) sebagai langkah forensik.
- **Password Strength Checker:** Frontend menyediakan indikator kekuatan kata sandi saat pengguna akan mengenkripsi, mendorong penggunaan *passphrase* yang kuat.

---

## 6. Kesimpulan
Proyek **CipherVault** berhasil mendemonstrasikan integrasi yang kuat antara rekayasa perangkat lunak modern (Laravel + Vite/Tailwind) dan prinsip-prinsip kriptografi tingkat lanjut. Penggunaan **AES-256-GCM** dipadukan dengan **PBKDF2 (310.000 iterasi)** di sisi klien berhasil menciptakan lingkungan *Zero-Knowledge* yang menjamin privasi pengguna secara matematis. Aplikasi ini sangat layak diajukan sebagai karya akhir atau tugas besar pada mata kuliah Kriptografi.
