<?php

namespace Database\Factories;

use App\Models\UserLink;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UserLinkFactory extends Factory
{
    protected $model = UserLink::class;

    public function definition(): array
    {
        return [
            'user_id'    => $this->faker->randomNumber(),
            'link'       => $this->faker->word(),
            'name'       => $this->faker->name(),
            'icon'       => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
