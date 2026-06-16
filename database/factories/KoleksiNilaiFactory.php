<?php

namespace Database\Factories;

use App\Models\KoleksiNilai;
use App\Models\Properti;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KoleksiNilai>
 */
class KoleksiNilaiFactory extends Factory
{
    protected $model = KoleksiNilai::class;

    public function definition(): array
    {
        return [
            'properti_id' => Properti::factory(),
            'status' => 'proses',
        ];
    }
}
