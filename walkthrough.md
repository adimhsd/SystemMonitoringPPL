# Walkthrough Implementation — Penyesuaian Batas Maksimal Mahasiswa per Kelompok (Max 20) & Indikator Warna

Perubahan aturan kapasitas jumlah mahasiswa dalam 1 kelompok PPL dari maksimal 10 menjadi **maksimal 20 mahasiswa per kelompok** serta penerapan **Indikator Visual Warna Tingkatan Jumlah Anggota** di berbagai elemen UI telah **SELESAI DITERAPKAN, DIVERIFIKASI, DAN DIPUSH KE GITHUB 100%**.

---

## 🛠️ Ringkasan Aturan Kapasitas & Indikator Visual

1. **Aturan Kapasitas Maksimal Baru**:
   - 1 Kelompok PPL dapat terdiri dari **minimal 1 hingga maksimal 20 mahasiswa**.
   - Validasi backend di [`PlottingController.php`](file:///c:/SystemMonitoringPPL/app/Http/Controllers/Admin/PlottingController.php) memastikan total mahasiswa anggota kelompok tidak melebihi 20 orang.

2. **Skema Indikator Warna Tingkatan Jumlah Anggota**:
   - **$\le$ 10 Mahasiswa** (Ukuran Standar Ideal): Badge Hijau `🟢 X Mahasiswa`
   - **11 – 15 Mahasiswa** (Ukuran Besar): Badge Oranye `⚠️ X Mahasiswa (Besar >10)`
   - **16 – 20 Mahasiswa** (Kapasitas Maksimal): Badge Merah `🔥 X Mahasiswa (Besar >15)`

3. **Penerapan pada Elemen Tampilan UI**:
   - **Tabel Master Data Kelompok** ([`admin/kelompok/index.blade.php`](file:///c:/SystemMonitoringPPL/resources/views/admin/kelompok/index.blade.php)): Menampilkan badge warna bertingkat pada kolom *Jumlah Anggota*.
   - **Tabel Plotting Kelompok** ([`admin/plotting/index.blade.php`](file:///c:/SystemMonitoringPPL/resources/views/admin/plotting/index.blade.php)): Menampilkan badge warna bertingkat pada kolom *Anggota Mahasiswa*.
   - **Form Plotting Kelompok** ([`admin/plotting/edit.blade.php`](file:///c:/SystemMonitoringPPL/resources/views/admin/plotting/edit.blade.php)): Memperbarui badge petunjuk kelompok menjadi `Minimal 1, Maksimal 20 Mahasiswa`.
   - **Detail Kelompok PPL** ([`admin/kelompok/show.blade.php`](file:///c:/SystemMonitoringPPL/resources/views/admin/kelompok/show.blade.php)): Memperbarui header section anggota kelompok dengan badge indikator warna bertingkat.

---

## 🧪 Hasil Automated Unit & Feature Tests

- **Status Pengujian**: `102 tests, 323 assertions` — **PASSED 100%**.
- **Status GitHub Push**: Pushed to `https://github.com/adimhsd/SystemMonitoringPPL.git` (commit `7b50156`).