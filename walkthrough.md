# Walkthrough Implementation — Perubahan Field & Kolom "Kelas" Menjadi "Konsentrasi"

Pengubahan seluruh istilah, kolom database, antarmuka pengguna (UI), laporan cetak, serta fitur Impor/Ekspor Excel dari **"Kelas"** menjadi **"Konsentrasi"** (Konsentrasi / Peminatan Mahasiswa) telah **SELESAI DITERAPKAN DI SELURUH AKUN USER, DIVERIFIKASI, DAN DIPUSH KE GITHUB 100%**.

---

## 🚀 Perubahan yang Diterapkan

### 1. 🗄️ Database Migration & Model
- Migration `2026_01_01_000013_rename_kelas_to_konsentrasi_in_anggota_kelompok_table.php`:
  - Mengubah nama kolom `kelas` menjadi `konsentrasi` pada tabel `anggota_kelompok`.
- Model `AnggotaKelompok` (`app/Models/AnggotaKelompok.php`) & `Mahasiswa` (`app/Models/Mahasiswa.php`):
  - Mengganti atribut `'kelas'` menjadi `'konsentrasi'` pada array `$fillable`.

### 2. 📊 Form Validation, Export & Import Excel
- **Controller Admin Mahasiswa (`Admin\MahasiswaController.php`)**:
  - Mengubah aturan validasi & pencarian data dari `kelas` menjadi `konsentrasi`.
- **Import Excel (`app/Imports/MahasiswaImport.php`)**:
  - Mendukung header kolom `konsentrasi` (serta *fallback* kompatibilitas `kelas`).
- **Export Excel (`app/Exports/MahasiswaExport.php` & `NilaiPplExport.php`)**:
  - Mengubah header kolom laporan dari `Kelas` menjadi `Konsentrasi`.

### 3. 🎨 Penyelarasan Antarmuka UI Seluruh Role User
- **Admin Master Data Mahasiswa (`admin/mahasiswa/index.blade.php`, `create`, `edit`)**:
  - Mengubah placeholder pencarian, header tabel, form input (*Konsentrasi / Peminatan*), serta petunjuk impor Excel.
- **Admin Plotting Kelompok (`admin/plotting/edit.blade.php`)**:
  - Mengubah header kolom tabel pilihan anggota menjadi **Konsentrasi**.
- **Dashboard Ketua Kelompok / Student (`ketua/dashboard.blade.php`)**:
  - Mengubah rincian anggota kelompok dari `Kelas` menjadi `Konsentrasi`.
- **Dashboard PIC Mitra (`pic/dashboard.blade.php`)**:
  - Mengubah rincian daftar mahasiswa magang dari `Kelas` menjadi `Konsentrasi`.

---

## 🧪 Hasil Automated Unit & Feature Tests

```bash
vendor/bin/phpunit
```

```
PHPUnit 11.5.42 by Sebastian Bergmann and contributors.

...............................................................  84 / 84 (100%)

Time: 00:10.583, Memory: 38.50 MB

OK (84 tests, 250 assertions)
```

- **Total Test Suite**: 84 Test Cases
- **Hasil**: `PASSED` 100%
- **Status GitHub Push**: Pushed to `https://github.com/adimhsd/SystemMonitoringPPL.git` (commit `64d24c4`).