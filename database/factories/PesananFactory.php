<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pesanan>
 */
class PesananFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama_toko' => fake()->company(),
            'alamat_toko' => fake()->address(),
            'tanggal' => fake()->date(),
            'return' => rand(0, 10),
            'terjual' => rand(0, 10),
            'sample' => rand(0, 10),
            'status' => rand(0, 1),
            'user_id' => rand(1, 10),
        ];
    }
}