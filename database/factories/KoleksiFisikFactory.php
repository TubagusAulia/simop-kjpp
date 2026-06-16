<?php

namespace Database\Factories;

use App\Models\KoleksiFisik;
use App\Models\Properti;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KoleksiFisik>
 */
class KoleksiFisikFactory extends Factory
{
    protected $model = KoleksiFisik::class;

    public function definition(): array
    {
        return [
            'properti_id' => Properti::factory(),
            'status' => 'proses',
            'wajib_list' => ['pondasi', 'struktur', 'arsitektur'],
        ];
    }
}
