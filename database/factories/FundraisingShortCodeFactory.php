<?php

namespace Database\Factories;

use App\Models\FundraisingShortCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FundraisingShortCodeFactory extends Factory
{
    protected $model = FundraisingShortCode::class;

    public function definition(): array
    {
        return [
            'fundraising_id' => $this->faker->randomNumber(),
            'code'           => $this->faker->word(),
            'created_at'     => Carbon::now(),
            'updated_at'     => Carbon::now(),
        ];
    }
}
