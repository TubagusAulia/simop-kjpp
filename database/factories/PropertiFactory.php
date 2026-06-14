<?php

namespace Database\Factories;

use App\Models\KoleksiDokumen;
use App\Models\KoleksiFisik;
use App\Models\KoleksiNilai;
use App\Models\Properti;
use App\Models\Proyek;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Properti>
 */
class PropertiFactory extends Factory
{
    protected $model = Properti::class;

    public function definition(): array
    {
        return [
            'proyek_id' => Proyek::factory(),
            'tipe_properti' => fake()->randomElement(['tanah', 'bangunan', 'tanah_bangunan']),
            'nama_properti' => fake()->sentence(2),
            'lokasi' => fake()->address(),
            'kategori' => fake()->randomElement(['komersial', 'residential', 'industrial']),
        ];
    }

    /**
     * Configure the model factory to create associated koleksi records.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Properti $properti) {
            KoleksiDokumen::factory()->create(['properti_id' => $properti->id]);
            KoleksiFisik::factory()->create(['properti_id' => $properti->id]);
            KoleksiNilai::factory()->create(['properti_id' => $properti->id]);
        });
    }
}
