# Walkthrough Implementation — Perbaikan Halaman Ubah Password Akun

Perbaikan menu **`🔑 Ubah Password`** pada dropdown profil pengguna (`/change-password-form`) untuk semua role (khususnya akun Ketua Kelompok / Mahasiswa) telah **SELESAI DITERAPKAN, DIVERIFIKASI, DAN DIPUSH KE GITHUB 100%**.

---

## 🛠️ Penyebab & Perbaikan Bug

### 1. Penyebab Error
Pada berkas `routes/web.php`, route `/change-password-form` dan `/change-password` memanggil `[ProfileController::class, 'showChangePasswordForm']`. Namun controller `ProfileController` tidak ada (karena handler ganti password berada di dalam `App\Http\Controllers\Auth\AuthController.php` / `AuthenticatedSessionController`), sehingga menyebabkan error 500 (*Target class ProfileController does not exist*).

### 2. Solusi Perbaikan
1. **Pembaruan Route Target (`routes/web.php`)**:
   Mengarahkan route pengubah password ke `AuthenticatedSessionController` (`AuthController`):
   ```php
   Route::get('/change-password', [AuthenticatedSessionController::class, 'showChangePasswordForm'])->name('password.change');
   Route::get('/change-password-form', [AuthenticatedSessionController::class, 'showChangePasswordForm'])->name('password.change.form');
   Route::post('/change-password', [AuthenticatedSessionController::class, 'updatePassword'])->name('password.change.update');
   Route::post('/update-password', [AuthenticatedSessionController::class, 'updatePassword'])->name('password.update');
   ```
2. **Pembaruan Form Action (`resources/views/auth/change-password.blade.php`)**:
   - Menyelaraskan form action ke `route('password.change.update')`.
   - Menambahkan tombol *&larr; Batal / Kembali* jika penggantian password dilakukan secara mandiri dari dropdown profil.

---

## 🧪 Hasil Automated Unit & Feature Tests

```bash
vendor/bin/phpunit
```

```
PHPUnit 11.5.42 by Sebastian Bergmann and contributors.

...............................................................  78 / 78 (100%)

Time: 00:07.662, Memory: 38.50 MB

OK (78 tests, 232 assertions)
```

- **Total Test Suite**: 78 Test Cases
- **Hasil**: `PASSED` 100%
- **Status GitHub Push**: Pushed to `https://github.com/adimhsd/SystemMonitoringPPL.git` (commit `ee2c5a8`).