<?php

namespace Tests\Unit\Models;

use App\Models\Properti;
use App\Models\Proyek;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertiTest extends TestCase
{
    use RefreshDatabase;

    public function test_properti_has_correct_fillable_attributes(): void
    {
        $properti = new Properti();

        $this->assertEquals([
            'proyek_id',
            'tipe_properti',
            'nama_properti',
            'lokasi',
            'kategori',
        ], $properti->getFillable());
    }

    public function test_properti_belongs_to_proyek(): void
    {
        $proyek = Proyek::factory()->create();
        $properti = Properti::factory()->create(['proyek_id' => $proyek->id]);

        $this->assertInstanceOf(Proyek::class, $properti->proyek);
        $this->assertEquals($proyek->id, $properti->proyek->id);
    }

    public function test_properti_has_many_dokumens(): void
    {
        $properti = Properti::factory()->create();

        $this->assertNotNull($properti->dokumens);
    }

    public function test_properti_has_one_koleksi_dokumen(): void
    {
        $properti = Properti::factory()->create();

        $this->assertNotNull($properti->koleksiDokumen);
    }

    public function test_properti_has_one_koleksi_fisik(): void
    {
        $properti = Properti::factory()->create();

        $this->assertNotNull($properti->koleksiFisik);
    }

    public function test_properti_has_one_koleksi_nilai(): void
    {
        $properti = Properti::factory()->create();

        $this->assertNotNull($properti->koleksiNilai);
    }
}
