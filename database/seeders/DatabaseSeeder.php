<?php

namespace Database\Seeders;

use App\Models\AnggotaKelompok;
use App\Models\ConfigAplikasi;
use App\Models\KegiatanHarian;
use App\Models\KelompokPpl;
use App\Models\Mitra;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password = Hash::make('password');

        // 1. Admin
        $admin = User::create([
            'username' => 'admin',
            'password' => $password,
            'role' => 'admin',
            'nama_lengkap' => 'Administrator Unit PPL FEB',
            'no_hp' => '081234567890',
            'nip_nidn' => '197501012000031001',
            'must_change_password' => false,
            'is_active' => true,
        ]);

        // 2. DPL (3 Dosen Pembimbing)
        $dpl1 = User::create([
            'username' => 'dpl1',
            'password' => $password,
            'role' => 'dpl',
            'nama_lengkap' => 'Dr. Ahmad Hidayat, M.Si',
            'no_hp' => '081298765432',
            'nip_nidn' => '198001012005011001',
            'must_change_password' => false,
            'is_active' => true,
        ]);

        $dpl2 = User::create([
            'username' => 'dpl2',
            'password' => $password,
            'role' => 'dpl',
            'nama_lengkap' => 'Sri Rahayu, S.E., M.Ak',
            'no_hp' => '081345678901',
            'nip_nidn' => '198503152010122002',
            'must_change_password' => false,
            'is_active' => true,
        ]);

        $dpl3 = User::create([
            'username' => 'dpl3',
            'password' => $password,
            'role' => 'dpl',
            'nama_lengkap' => 'Budi Santoso, M.Kom',
            'no_hp' => '081456789012',
            'nip_nidn' => '199007202015041003',
            'must_change_password' => false,
            'is_active' => true,
        ]);

        // 3. PIC Mitra (3 Akun PIC Mitra — 1 PIC per Mitra)
        $pic1 = User::create([
            'username' => 'pic_bappeda',
            'password' => $password,
            'role' => 'pic_mitra',
            'nama_lengkap' => 'Haji Sobri, S.T.',
            'no_hp' => '085211223344',
            'nip_nidn' => '197802102003121002',
            'must_change_password' => false,
            'is_active' => true,
        ]);

        $pic2 = User::create([
            'username' => 'pic_tirta',
            'password' => $password,
            'role' => 'pic_mitra',
            'nama_lengkap' => 'Anita Wijaya, S.E.',
            'no_hp' => '085322334455',
            'nip_nidn' => null,
            'must_change_password' => false,
            'is_active' => true,
        ]);

        $pic3 = User::create([
            'username' => 'pic_kopiluwak',
            'password' => $password,
            'role' => 'pic_mitra',
            'nama_lengkap' => 'Dede Kurniadi',
            'no_hp' => '085433445566',
            'nip_nidn' => null,
            'must_change_password' => false,
            'is_active' => true,
        ]);

        // Master Mitra
        $mitra1 = Mitra::create([
            'nama_mitra' => 'BAPPEDA Kabupaten Kuningan',
            'kategori' => 'SKPD',
            'alamat' => 'Jl. Soekarno No. 1, Kuningan',
            'pic_user_id' => $pic1->id,
        ]);

        $mitra2 = Mitra::create([
            'nama_mitra' => 'PT Tirta Utama Kuningan',
            'kategori' => 'Swasta',
            'alamat' => 'Jl. Raya Cilimus No. 45, Kuningan',
            'pic_user_id' => $pic2->id,
        ]);

        $mitra3 = Mitra::create([
            'nama_mitra' => 'UMKM Kopi Luwak Kuningan',
            'kategori' => 'UMKM',
            'alamat' => 'Jl. Veteran No. 12, Kuningan',
            'pic_user_id' => $pic3->id,
        ]);

        // 4. Akun Kelompok & Kelompok PPL
        $ketua1 = User::create([
            'username' => 'ketua1',
            'password' => $password,
            'role' => 'ketua_kelompok',
            'nama_lengkap' => 'Kelompok 01 - BAPPEDA',
            'no_hp' => '087711223344',
            'nip_nidn' => null,
            'must_change_password' => false,
            'is_active' => true,
        ]);

        $kelompok1 = KelompokPpl::create([
            'nama_kelompok' => 'Kelompok 01 - BAPPEDA',
            'mitra_id' => $mitra1->id,
            'dpl_id' => $dpl1->id,
            'ketua_user_id' => $ketua1->id,
            'tahun_akademik' => '2025/2026',
            'status' => 'aktif',
        ]);

        AnggotaKelompok::create([
            'kelompok_id' => $kelompok1->id,
            'nim' => '2022081001',
            'nama' => 'Rizky Pratama',
            'prodi' => 'Bisnis Digital',
        ]);
        AnggotaKelompok::create([
            'kelompok_id' => $kelompok1->id,
            'nim' => '2022081002',
            'nama' => 'Maya Indah',
            'prodi' => 'Manajemen',
        ]);
        AnggotaKelompok::create([
            'kelompok_id' => $kelompok1->id,
            'nim' => '2022081003',
            'nama' => 'Bagus Setiawan',
            'prodi' => 'Akuntansi',
        ]);

        $ketua2 = User::create([
            'username' => 'ketua2',
            'password' => $password,
            'role' => 'ketua_kelompok',
            'nama_lengkap' => 'Kelompok 02 - PT Tirta Utama',
            'no_hp' => '087722334455',
            'nip_nidn' => null,
            'must_change_password' => false,
            'is_active' => true,
        ]);

        $kelompok2 = KelompokPpl::create([
            'nama_kelompok' => 'Kelompok 02 - PT Tirta Utama',
            'mitra_id' => $mitra2->id,
            'dpl_id' => $dpl1->id, // Total dpl1: 3 + 3 = 6 mhs (<= 10)
            'ketua_user_id' => $ketua2->id,
            'tahun_akademik' => '2025/2026',
            'status' => 'aktif',
        ]);

        AnggotaKelompok::create([
            'kelompok_id' => $kelompok2->id,
            'nim' => '2022081005',
            'nama' => 'Siti Nurhaliza',
            'prodi' => 'Manajemen',
        ]);
        AnggotaKelompok::create([
            'kelompok_id' => $kelompok2->id,
            'nim' => '2022081006',
            'nama' => 'Hendra Gunawan',
            'prodi' => 'Bisnis Digital',
        ]);
        AnggotaKelompok::create([
            'kelompok_id' => $kelompok2->id,
            'nim' => '2022081007',
            'nama' => 'Nadia Putri',
            'prodi' => 'Akuntansi',
        ]);

        $ketua3 = User::create([
            'username' => 'ketua3',
            'password' => $password,
            'role' => 'ketua_kelompok',
            'nama_lengkap' => 'Kelompok 03 - UMKM Kopi Luwak',
            'no_hp' => '087733445566',
            'nip_nidn' => null,
            'must_change_password' => false,
            'is_active' => true,
        ]);

        $kelompok3 = KelompokPpl::create([
            'nama_kelompok' => 'Kelompok 03 - UMKM Kopi Luwak',
            'mitra_id' => $mitra3->id,
            'dpl_id' => $dpl2->id,
            'ketua_user_id' => $ketua3->id,
            'tahun_akademik' => '2025/2026',
            'status' => 'aktif',
        ]);

        AnggotaKelompok::create([
            'kelompok_id' => $kelompok3->id,
            'nim' => '2022081010',
            'nama' => 'Andi Wijaya',
            'prodi' => 'Akuntansi',
        ]);
        AnggotaKelompok::create([
            'kelompok_id' => $kelompok3->id,
            'nim' => '2022081011',
            'nama' => 'Rina Marlina',
            'prodi' => 'Manajemen',
        ]);

        // 5. Sample Logbook Harian
        KegiatanHarian::create([
            'kelompok_id' => $kelompok1->id,
            'tanggal' => now()->subDays(2)->format('Y-m-d'),
            'waktu_mulai' => '08:00:00',
            'waktu_selesai' => '16:00:00',
            'deskripsi_kegiatan' => 'Observasi lapangan dan pengenalan alur kerja unit perencanaan BAPPEDA.',
            'foto_dokumentasi' => 'logbooks/sample_foto_1.jpg',
            'dilihat_mitra' => true,
            'dilihat_mitra_at' => now()->subDays(2)->addHours(2),
            'dilihat_dpl' => true,
            'dilihat_dpl_at' => now()->subDays(2)->addHours(4),
            'terlambat' => false,
        ]);

        KegiatanHarian::create([
            'kelompok_id' => $kelompok1->id,
            'tanggal' => now()->subDays(1)->format('Y-m-d'),
            'waktu_mulai' => '08:30:00',
            'waktu_selesai' => '15:30:00',
            'deskripsi_kegiatan' => 'Penyusunan rekapitulasi data potensi ekonomi daerah berbasis spreadsheet.',
            'foto_dokumentasi' => 'logbooks/sample_foto_2.jpg',
            'dilihat_mitra' => true,
            'dilihat_mitra_at' => now()->subDays(1)->addHours(1),
            'dilihat_dpl' => false,
            'dilihat_dpl_at' => null,
            'terlambat' => false,
        ]);

        // 6. Config Konversi Nilai Huruf
        ConfigAplikasi::set('skala_nilai_huruf', [
            ['min' => 81.00, 'max' => 100.00, 'huruf' => 'A'],
            ['min' => 75.00, 'max' => 80.99,  'huruf' => 'AB'],
            ['min' => 69.00, 'max' => 74.99,  'huruf' => 'B'],
            ['min' => 63.00, 'max' => 68.99,  'huruf' => 'BC'],
            ['min' => 57.00, 'max' => 62.99,  'huruf' => 'C'],
            ['min' => 51.00, 'max' => 56.99,  'huruf' => 'CD'],
            ['min' => 45.00, 'max' => 50.99,  'huruf' => 'D'],
            ['min' => 0.00,  'max' => 44.99,  'huruf' => 'E'],
        ]);

        // Sample Notifikasi
        Notifikasi::create([
            'user_id' => $ketua1->id,
            'judul' => 'Pengingat Logbook',
            'pesan' => 'Jangan lupa mengisi logbook kegiatan harian PPL hari ini.',
            'tipe' => 'reminder',
            'is_read' => false,
        ]);
    }
}
