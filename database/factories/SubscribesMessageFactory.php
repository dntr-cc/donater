<?php

namespace Database\Factories;

use App\Models\SubscribesMessage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class SubscribesMessageFactory extends Factory
{
    protected $model = SubscribesMessage::class;

    public function definition(): array
    {
        return [
            'subscribes_id' => $this->faker->randomNumber(),
            'frequency'     => $this->faker->word(),
            'schedule_time' => Carbon::now(),
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        ];
    }
}
