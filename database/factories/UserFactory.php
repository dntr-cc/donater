<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'username' => fake()->username(),
            'telegram_id' => fake()->unique()->randomNumber(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'avatar' => '/images/avatars/avatar.jpeg',
            'is_premium' => rand() % 2 === 0,
        ];
    }
}
