<?php

namespace Database\Factories;

use App\Models\Referral;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ReferralFactory extends Factory
{
    protected $model = Referral::class;

    public function definition(): array
    {
        return [
            'user_id'     => $this->faker->randomNumber(),
            'refferal_id' => $this->faker->randomNumber(),
            'created_at'  => Carbon::now(),
            'updated_at'  => Carbon::now(),
        ];
    }
}
