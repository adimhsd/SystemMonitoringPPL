<?php

namespace Tests\Feature;

use App\Models\KegiatanHarian;
use App\Models\KelompokPpl;
use App\Models\Mitra;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogbookReviewAndVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $dpl;
    protected User $pic;
    protected KegiatanHarian $logbook;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->dpl = User::where('role', 'dpl')->first();
        $this->pic = User::where('role', 'pic_mitra')->first();
        $this->logbook = KegiatanHarian::first();
    }

    public function test_dpl_can_view_assigned_group_logbooks(): void
    {
        $this->logbook->kelompok->update(['dpl_id' => $this->dpl->id]);

        $response = $this->actingAs($this->dpl)->get('/dpl/logbook');
        $response->assertStatus(200);
        $response->assertSee('Pemantauan Logbook');
    }

    public function test_dpl_can_mark_logbook_as_viewed_and_send_notification(): void
    {
        $this->logbook->kelompok->update(['dpl_id' => $this->dpl->id]);
        $this->logbook->update(['dilihat_dpl' => false, 'dilihat_dpl_at' => null]);

        $response = $this->actingAs($this->dpl)->post("/dpl/logbook/{$this->logbook->id}/viewed");

        $response->assertRedirect();
        $this->assertDatabaseHas('kegiatan_harian', [
            'id' => $this->logbook->id,
            'dilihat_dpl' => true,
        ]);

        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $this->logbook->kelompok->ketua_user_id,
            'judul' => 'Logbook Di-Approve DPL',
        ]);
    }

    public function test_pic_mitra_can_mark_logbook_as_viewed(): void
    {
        $mitra = Mitra::where('pic_user_id', $this->pic->id)->first();
        $this->logbook->kelompok->update(['mitra_id' => $mitra->id]);
        $this->logbook->update(['dilihat_mitra' => false, 'dilihat_mitra_at' => null]);

        $response = $this->actingAs($this->pic)->post("/pic/logbook/{$this->logbook->id}/viewed");

        $response->assertRedirect();
        $this->assertDatabaseHas('kegiatan_harian', [
            'id' => $this->logbook->id,
            'dilihat_mitra' => true,
        ]);

        $this->assertDatabaseHas('notifikasi', [
            'user_id' => $this->logbook->kelompok->ketua_user_id,
            'judul' => 'Logbook Di-Approve Pembimbing Mitra',
        ]);
    }

    public function test_unauthorized_dpl_cannot_mark_other_group_logbook(): void
    {
        $otherDpl = User::where('role', 'dpl')->where('id', '!=', $this->logbook->kelompok->dpl_id)->first();

        $response = $this->actingAs($otherDpl)->post("/dpl/logbook/{$this->logbook->id}/viewed");

        $response->assertStatus(403);
    }
}
