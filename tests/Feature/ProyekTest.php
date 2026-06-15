<?php

namespace Tests\Feature;

use App\Models\Proyek;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProyekTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_proyek_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('proyek.index'));

        $response->assertStatus(200);
        $response->assertViewIs('proyek.index');
    }

    public function test_admin_can_create_proyek(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $karyawan = User::factory()->create(['role' => 'karyawan']);

        $response = $this->actingAs($admin)->post(route('proyek.store'), [
            'nama_proyek' => 'Proyek Test',
            'deskripsi' => 'Deskripsi proyek test',
            'start_date' => '2026-01-01',
            'due_date' => '2026-12-31',
            'tipe_properti' => 'tanah_bangunan',
            'user_ids' => [$karyawan->id],
            'kontrak_file' => \Illuminate\Http\UploadedFile::fake()->create('kontrak.pdf', 100, 'application/pdf'),
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('proyek', ['nama_proyek' => 'Proyek Test']);
    }

    public function test_non_admin_cannot_create_proyek(): void
    {
        $user = User::factory()->create(['role' => 'karyawan']);

        $response = $this->actingAs($user)->get(route('proyek.create'));

        $response->assertStatus(403);
    }

    public function test_admin_can_view_proyek_show(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $proyek = Proyek::factory()->create(['created_by' => $admin->id]);

        $response = $this->actingAs($admin)->get(route('proyek.show', $proyek));

        $response->assertStatus(200);
        $response->assertViewIs('proyek.show');
    }

    public function test_karyawan_can_view_assigned_proyek(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $karyawan = User::factory()->create(['role' => 'karyawan']);
        $proyek = Proyek::factory()->create(['created_by' => $admin->id]);
        \App\Models\AlokasiProyek::create([
            'proyek_id' => $proyek->id,
            'user_id' => $karyawan->id,
            'allocated_by' => $admin->id,
            'allocated_at' => now(),
        ]);

        $response = $this->actingAs($karyawan)->get(route('proyek.show', $proyek));

        $response->assertStatus(200);
    }

    public function test_karyawan_cannot_view_unassigned_proyek(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $karyawan = User::factory()->create(['role' => 'karyawan']);
        $proyek = Proyek::factory()->create(['created_by' => $admin->id]);

        $response = $this->actingAs($karyawan)->get(route('proyek.show', $proyek));

        $response->assertStatus(403);
    }
}
