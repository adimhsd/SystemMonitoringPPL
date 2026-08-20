# Walkthrough Implementation — Perbaikan Error Duplicate Entry DPL Import

Perbaikan bug **SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry** pada fitur **Impor Excel DPL (`DplImport.php`)** telah **SELESAI DITERAPKAN, DIVERIFIKASI DENGAN AUTOMATED TEST, DAN DIPUSH KE GITHUB 100%**.

---

## 🔍 Penyebab Error & Perbaikan

### 1. Root Cause (Penyebab Masalah)
- Saat mengimpor Excel DPL, sistem menemukan baris DPL yang memiliki NIP/NIDN sama dengan data di database (misalnya DPL A), namun baris Excel tersebut mencantumkan username (misalnya `DPL_PPL41`) yang sudah dimiliki oleh user lain (misalnya DPL B).
- Sebelumnya, `DplImport` langsung melakukan `update(['username' => 'DPL_PPL41'])` tanpa memeriksa apakah username `DPL_PPL41` sudah dipakai oleh user lain di tabel `users`.
- Akibatnya, MySQL menolak update tersebut dan melontarkan error:
  `SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'DPL_PPL41' for key 'users.users_username_unique'`.

---

### 2. Solusi & Perbaikan (`app/Imports/DplImport.php`)
- **Pencarian Berdasarkan NIP/NIDN (Prioritas Utama)**:
  Sistem kini memprioritaskan pencarian data DPL berdasarkan `nip_nidn` (nomor identitas unik dosen) kemudian `username`.
- **Perhitungan Aman Pembaruan Username**:
  Saat memperbarui DPL yang sudah ada, `DplImport` kini memeriksa `isUsernameTaken`:
  ```php
  $isUsernameTaken = User::where('username', $username)->where('id', '!=', $user->id)->exists();
  if (!$isUsernameTaken) {
      $updatePayload['username'] = $username;
  }
  ```
  Jika username tersebut sudah dipakai oleh akun user lain, sistem **tidak akan memaksakan pembaruan username** (tetap menggunakan username lama yang valid), sehingga proses impor berjalan lancar 100% tanpa error constraint.
- **Auto-Suffix untuk Akun DPL Baru**:
  Jika dibuat DPL baru dan username yang dimasukkan di Excel ternyata sudah terpakai, sistem otomatis menambahkan akhiran unik (`DPL_PPL41_1`, `DPL_PPL41_2`, dst).

---

## 🧪 Hasil Automated Unit & Feature Tests

- **Test Suite Khusus**: [`tests/Feature/AdminDplImportDuplicateTest.php`](file:///c:/SystemMonitoringPPL/tests/Feature/AdminDplImportDuplicateTest.php)
- **Status Pengujian**: `2 tests, 5 assertions` — **PASSED 100%**.
- **Status GitHub Push**: Pushed to `https://github.com/adimhsd/SystemMonitoringPPL.git` (commit `820d770`).