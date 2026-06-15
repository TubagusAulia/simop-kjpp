<?php

namespace Tests\Unit\Models;

use App\Models\Proyek;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProyekTest extends TestCase
{
    use RefreshDatabase;

    public function test_proyek_has_correct_fillable_attributes(): void
    {
        $proyek = new Proyek;

        $this->assertEquals([
            'nama_proyek',
            'deskripsi',
            'start_date',
            'due_date',
            'status',
            'current_phase',
            'kontrak_file',
            'created_by',
            'finish_requested',
            'finish_requested_by',
            'finish_requested_at',
        ], $proyek->getFillable());
    }

    public function test_proyek_has_correct_casts(): void
    {
        $proyek = new Proyek;

        $casts = $proyek->getCasts();

        $this->assertEquals('date', $casts['start_date']);
        $this->assertEquals('date', $casts['due_date']);
        $this->assertEquals('datetime', $casts['finish_requested_at']);
        $this->assertEquals('boolean', $casts['finish_requested']);
    }

    public function test_proyek_belongs_to_creator(): void
    {
        $user = User::factory()->create();
        $proyek = Proyek::factory()->create(['created_by' => $user->id]);

        $this->assertInstanceOf(User::class, $proyek->creator);
        $this->assertEquals($user->id, $proyek->creator->id);
    }

    public function test_proyek_has_one_properti(): void
    {
        $proyek = Proyek::factory()->create();

        $this->assertNotNull($proyek->properti);
    }
}
