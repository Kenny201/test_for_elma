<?php

namespace Database\Factories;

use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Factory;

class SourceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'blockchain.com'
        ];
    }
}
