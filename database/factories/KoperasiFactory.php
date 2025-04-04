<?php

namespace Database\Factories;

use App\Models\Koperasi;
use Illuminate\Database\Eloquent\Factories\Factory;

class KoperasiFactory extends Factory
{
    protected $model = Koperasi::class;

    public function definition()
    {
        return [
            'kode' => 'K' . $this->faker->unique()->numberBetween(1, 100),
            'nama' => $this->faker->company,
            'alamat' => $this->faker->address,
        ];
    }
}
