<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UserCodeFactory extends Factory
{
    protected $model = UserCode::class;

    public function definition(): array
    {
        return [
            'hash'       => $this->faker->word(),
            'user_id'    => User::factory()->create()->getId(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    /**
     * @param int $userId
     * @return UserCodeFactory
     */
    public function forUser(int $userId): UserCodeFactory
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $userId,
        ]);
    }
}
