<?php

namespace Database\Factories;

use App\Models\DeepLink;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DeepLinkFactory extends Factory
{
    protected $model = DeepLink::class;

    public function definition(): array
    {
        return [
            'volunteer_id' => $this->faker->randomNumber(),
            'hash'         => $this->faker->word(),
            'amount'       => $this->faker->word(),
            'started_at'   => Carbon::now(),
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ];
    }
}
