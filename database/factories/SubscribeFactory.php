<?php

namespace Database\Factories;

use App\Models\Subscribe;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SubscribeFactory extends Factory
{
    protected $model = Subscribe::class;

    public function definition(): array
    {
        return [
            'user_id'      => $this->faker->randomNumber(),
            'volunteer_id' => $this->faker->randomNumber(),
            'amount'       => $this->faker->randomNumber(),
            'scheduled_at' => Carbon::now(),
            'use_random'   => $this->faker->boolean(),
            'created_at'   => Carbon::now(),
            'updated_at'   => Carbon::now(),
        ];
    }
}
