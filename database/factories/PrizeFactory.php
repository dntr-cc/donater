<?php

namespace Database\Factories;

use App\Models\Prize;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class PrizeFactory extends Factory
{
    protected $model = Prize::class;

    public function definition(): array
    {
        return [
            'name'           => $this->faker->name(),
            'description'    => $this->faker->text(),
            'fundraising_id' => $this->faker->randomNumber(),
            'user_id'        => $this->faker->randomNumber(),
            'raffle_type'    => $this->faker->word(),
            'raffle_winners' => $this->faker->randomNumber(),
            'raffle_price'   => $this->faker->randomFloat(),
            'avatar'         => $this->faker->word(),
            'available_type' => $this->faker->word(),
            'created_at'     => Carbon::now(),
            'updated_at'     => Carbon::now(),
        ];
    }
}
