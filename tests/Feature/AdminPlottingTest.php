<?php

namespace Tests\Feature;

use App\Models\KelompokPpl;
use App\Models\Mitra;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminPlottingTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected KelompokPpl $kelompok;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->admin = User::where('role', 'admin')->first() ?? User::create([
            'username' => 'admin_test_plotting',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'nama_lengkap' => 'Admin Plotting Test',
            'must_change_password' => false,
            'is_active' => true,
        ]);

        $ketuaUser = User::create([
            'username' => 'ketua_plotting_test',
            'password' => Hash::make('password'),
            'role' => 'ketua_kelompok',
            'nama_lengkap' => 'Ketua Plotting Test',
            'must_change_password' => false,
            'is_active' => true,
        ]);

        $mitra = Mitra::create([
            'nama_mitra' => 'Mitra Plotting Test',
            'kategori' => 'SKPD',
        ]);

        $this->kelompok = KelompokPpl::create([
            'nama_kelompok' => 'Kelompok Plotting Test',
            'tahun_akademik' => '2025/2026',
            'ketua_user_id' => $ketuaUser->id,
            'mitra_id' => $mitra->id,
            'status' => 'aktif',
        ]);
    }

    public function test_admin_can_access_plotting_edit_page(): void
    {
        $response = $this->actingAs($this->admin)->get("/admin/plotting/{$this->kelompok->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Kelompok Plotting Test');
    }

    public function test_admin_can_access_plotting_index_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/plotting');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_kelompok_create_page(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/kelompok/create');

        $response->assertStatus(200);
    }
}
