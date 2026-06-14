<?php

namespace Database\Factories;

use App\Models\KoleksiDokumen;
use App\Models\Properti;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KoleksiDokumen>
 */
class KoleksiDokumenFactory extends Factory
{
    protected $model = KoleksiDokumen::class;

    public function definition(): array
    {
        return [
            'properti_id' => Properti::factory(),
            'status' => 'proses',
            'wajib_list' => ['sertifikat', 'imb', 'pbb'],
        ];
    }
}
