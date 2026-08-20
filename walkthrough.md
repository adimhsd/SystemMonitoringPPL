# Walkthrough Implementation — Penyesuaian Batas Maksimal Bimbingan DPL (Max 30) & Indikator Warna

Perubahan aturan kapasitas beban bimbingan DPL dari maksimal 10 menjadi **maksimal 30 mahasiswa per DPL** serta penerapan **Indikator Visual Warna Tingkatan Beban** di berbagai elemen UI telah **SELESAI DITERAPKAN, DIVERIFIKASI, DAN DIPUSH KE GITHUB 100%**.

---

## 🛠️ Ringkasan Aturan Kapasitas & Indikator Visual

1. **Aturan Kapasitas Maksimal Baru**:
   - 1 DPL dapat membimbing hingga **maksimal 30 mahasiswa**.
   - Validasi backend di [`PlottingController.php`](file:///c:/SystemMonitoringPPL/app/Http/Controllers/Admin/PlottingController.php) memastikan total bimbingan DPL tidak melebihi 30 mahasiswa.

2. **Skema Indikator Warna Tingkatan Beban**:
   - **$\le$ 10 Mahasiswa** (Standar Ideal): Badge Hijau `🟢 X / 30 Mahasiswa`
   - **11 – 20 Mahasiswa** (Melebihi Standar 10): Badge Oranye `⚠️ X / 30 Mahasiswa`
   - **21 – 30 Mahasiswa** (Beban Tinggi): Badge Merah `🔥 X / 30 Mahasiswa`
   - **30 Mahasiswa** (Kapasitas Penuh): Badge Merah Pekat `🔴 30 / 30 Mahasiswa (Penuh)`

3. **Penerapan pada Elemen Tampilan UI**:
   - **Tabel Master Data DPL** ([`admin/dpl/index.blade.php`](file:///c:/SystemMonitoringPPL/resources/views/admin/dpl/index.blade.php)): Menampilkan badge warna bertingkat pada kolom *Beban Mahasiswa Bimbingan*.
   - **Form Plotting Kelompok** ([`admin/plotting/edit.blade.php`](file:///c:/SystemMonitoringPPL/resources/views/admin/plotting/edit.blade.php)): Menampilkan badge status beban pada opsi dropdown pilihan DPL.
   - **Dashboard DPL** ([`dpl/dashboard.blade.php`](file:///c:/SystemMonitoringPPL/resources/views/dpl/dashboard.blade.php)): Memperbarui widget status bimbingan DPL dengan kapasitas penyebut `/ 30` dan warna dinamis.

---

## 🧪 Hasil Automated Unit & Feature Tests

- **Status Pengujian**: `101 tests, 318 assertions` — **PASSED 100%**.
- **Status GitHub Push**: Pushed to `https://github.com/adimhsd/SystemMonitoringPPL.git` (commit `3e86820`).