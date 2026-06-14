<?php

namespace Tests\Unit\Models;

use App\Models\KoleksiDokumen;
use App\Models\Properti;
use App\Models\Proyek;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KoleksiDokumenTest extends TestCase
{
    use RefreshDatabase;

    public function test_koleksi_dokumen_has_correct_fillable_attributes(): void
    {
        $koleksi = new KoleksiDokumen();

        $this->assertEquals([
            'properti_id',
            'status',
            'wajib_list',
            'completed_at',
            'completed_by',
        ], $koleksi->getFillable());
    }

    public function test_koleksi_dokumen_casts_wajib_list_to_array(): void
    {
        $koleksi = new KoleksiDokumen();
        $casts = $koleksi->getCasts();

        $this->assertEquals('array', $casts['wajib_list']);
        $this->assertEquals('datetime', $casts['completed_at']);
    }

    public function test_koleksi_dokumen_belongs_to_properti(): void
    {
        $proyek = Proyek::factory()->create();
        $properti = Properti::factory()->create(['proyek_id' => $proyek->id]);
        $koleksi = KoleksiDokumen::factory()->create(['properti_id' => $properti->id]);

        $this->assertInstanceOf(Properti::class, $koleksi->properti);
        $this->assertEquals($properti->id, $koleksi->properti->id);
    }

    public function test_koleksi_dokumen_returns_empty_array_when_wajib_list_is_null(): void
    {
        $proyek = Proyek::factory()->create();
        $properti = Properti::factory()->create(['proyek_id' => $proyek->id]);
        $koleksi = KoleksiDokumen::factory()->create([
            'properti_id' => $properti->id,
            'wajib_list' => null,
        ]);

        $this->assertEquals([], $koleksi->getWajibKeys());
    }

    public function test_koleksi_dokumen_returns_wajib_keys(): void
    {
        $proyek = Proyek::factory()->create();
        $properti = Properti::factory()->create(['proyek_id' => $proyek->id]);
        $koleksi = KoleksiDokumen::factory()->create([
            'properti_id' => $properti->id,
            'wajib_list' => ['sertifikat', 'imb', 'pbb'],
        ]);

        $this->assertEquals(['sertifikat', 'imb', 'pbb'], $koleksi->getWajibKeys());
    }

    public function test_koleksi_dokumen_is_wajib_uploaded_returns_true_when_empty(): void
    {
        $proyek = Proyek::factory()->create();
        $properti = Properti::factory()->create(['proyek_id' => $proyek->id]);
        $koleksi = KoleksiDokumen::factory()->create([
            'properti_id' => $properti->id,
            'wajib_list' => [],
        ]);

        $this->assertTrue($koleksi->isWajibUploaded());
    }

    public function test_koleksi_dokumen_progression_is_zero_when_no_docs(): void
    {
        $proyek = Proyek::factory()->create();
        $properti = Properti::factory()->create(['proyek_id' => $proyek->id]);
        $koleksi = KoleksiDokumen::factory()->create([
            'properti_id' => $properti->id,
            'wajib_list' => ['sertifikat'],
        ]);

        $this->assertEquals(0, $koleksi->getProgression());
    }

    public function test_koleksi_dokumen_mark_complete_updates_status_and_timestamp(): void
    {
        $proyek = Proyek::factory()->create();
        $properti = Properti::factory()->create(['proyek_id' => $proyek->id]);
        $koleksi = KoleksiDokumen::factory()->create([
            'properti_id' => $properti->id,
            'status' => 'proses',
        ]);

        $koleksi->markComplete(1);

        $this->assertEquals('selesai', $koleksi->fresh()->status);
        $this->assertEquals(1, $koleksi->fresh()->completed_by);
        $this->assertNotNull($koleksi->fresh()->completed_at);
    }

    public function test_koleksi_dokumen_get_task_returns_null_when_complete(): void
    {
        $proyek = Proyek::factory()->create();
        $properti = Properti::factory()->create(['proyek_id' => $proyek->id]);
        $koleksi = KoleksiDokumen::factory()->create([
            'properti_id' => $properti->id,
            'status' => 'selesai',
        ]);

        $this->assertNull($koleksi->getTask());
    }
}
