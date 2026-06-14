<?php

namespace Database\Factories;

use App\Models\Proyek;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Proyek>
 */
class ProyekFactory extends Factory
{
    protected $model = Proyek::class;

    public function definition(): array
    {
        return [
            'nama_proyek' => fake()->sentence(3),
            'deskripsi' => fake()->paragraph(),
            'start_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'due_date' => fake()->dateTimeBetween('now', '+3 months'),
            'status' => fake()->randomElement(['aktif', 'selesai', 'ditunda']),
            'current_phase' => fake()->randomElement(['dokumen', 'fisik', 'dinilai', 'selesai']),
            'created_by' => User::factory(),
            'finish_requested' => false,
        ];
    }
}
