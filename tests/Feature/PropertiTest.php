<?php

namespace Tests\Feature;

use App\Models\Properti;
use App\Models\Proyek;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertiTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_properti_on_proyek_show(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $proyek = Proyek::factory()->create(['created_by' => $admin->id]);
        $properti = Properti::factory()->create(['proyek_id' => $proyek->id]);

        $response = $this->actingAs($admin)->get(route('proyek.show', $proyek));

        $response->assertStatus(200);
        $response->assertViewHas('proyek');
    }

    public function test_authenticated_user_can_access_laporan_page(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get(route('laporan.project'));

        $response->assertStatus(200);
    }
}
