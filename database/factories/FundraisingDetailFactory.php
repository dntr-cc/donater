<?php

namespace Database\Factories;

use App\Models\FundraisingDetail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FundraisingDetailFactory extends Factory
{
    protected $model = FundraisingDetail::class;

    public function definition(): array
    {
        return [
            'data'       => $this->faker->words(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
