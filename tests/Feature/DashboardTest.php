<?php

namespace Tests\Feature;

use App\Models\Proyek;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboards.index');
    }

    public function test_authenticated_karyawan_can_access_dashboard(): void
    {
        $karyawan = User::factory()->create(['role' => 'karyawan']);

        $response = $this->actingAs($karyawan)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('dashboards.index');
    }

    public function test_dashboard_contains_project_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        Proyek::factory()->create(['created_by' => $admin->id]);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('proyeks');
        $response->assertViewHas('pieData');
        $response->assertViewHas('barData');
    }
}
