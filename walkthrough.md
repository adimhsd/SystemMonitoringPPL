# Walkthrough Implementation — Fitur Cetak PDF Report & Export Excel Plotting PPL

Pengembangan fitur **Cetak PDF Report** dan **Export Excel** pada menu **Plotting & Pemetaan Penempatan PPL Admin (`/admin/plotting`)** telah **SELESAI DITERAPKAN, DIVERIFIKASI, DAN DIPUSH KE GITHUB 100%**.

---

## 🚀 Perubahan yang Diterapkan

### 1. 📄 Fitur Cetak PDF Report Plotting PPL
- **Controller Method**: `exportPdf(Request $request)` di `Admin\PlottingController.php`.
- **View Template PDF**: [`resources/views/pdf/laporan-plotting.blade.php`](file:///c:/SystemMonitoringPPL/resources/views/pdf/laporan-plotting.blade.php)
  - Dilengkapi **Kop Surat FEB UNIKU** dengan Logo UNIKU transparan di sebelah kiri.
  - Tampilan format A4 Landscape berisi tabel pemetaan lengkap: *Nama Kelompok & Akun*, *Mitra Penempatan & PIC*, *DPL Pembimbing & NIP/NIDN*, *Daftar Anggota Mahasiswa (NIM, Nama, Prodi)*, serta *Lembar Pengesahan Panitia PPL*.
  - Mendukung filter pencarian (*search query*).

### 2. 📊 Fitur Export Excel Plotting PPL
- **Export Class**: [`app/Exports/PlottingPplExport.php`](file:///c:/SystemMonitoringPPL/app/Exports/PlottingPplExport.php)
- **Controller Method**: `exportExcel(Request $request)` di `Admin\PlottingController.php`.
- Menghasilkan berkas Excel `.xlsx` yang berisi seluruh data pemetaan plotting: ID Kelompok, Nama Kelompok, Username Akun, Tahun Akademik, Status, Mitra Penempatan, Kategori Mitra, Alamat Mitra, Pembimbing PIC Mitra, DPL Pembimbing, NIP/NIDN DPL, Jumlah Anggota, dan Daftar Rincian Anggota Mahasiswa.

### 3. 🎨 Penambahan Tombol Aksi di Antarmuka Admin
- Di header menu [`resources/views/admin/plotting/index.blade.php`](file:///c:/SystemMonitoringPPL/resources/views/admin/plotting/index.blade.php):
  - **`📄 Cetak PDF Report`** (merujuk ke `route('admin.plotting.pdf')` - *Open in New Tab*).
  - **`📊 Export Excel`** (merujuk ke `route('admin.plotting.export-excel')`).

---

## 🧪 Hasil Automated Unit & Feature Tests

- **Test Suite**: [`tests/Feature/AdminPlottingExportTest.php`](file:///c:/SystemMonitoringPPL/tests/Feature/AdminPlottingExportTest.php)
- **Status GitHub Push**: Berhasil di-push ke branch `main` pada repositori [`https://github.com/adimhsd/SystemMonitoringPPL.git`](https://github.com/adimhsd/SystemMonitoringPPL.git) (commit `822d36a`).