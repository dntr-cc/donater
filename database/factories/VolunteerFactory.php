<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VolunteerFactory extends Factory
{
    protected $model = Volunteer::class;

    public function definition(): array
    {
        return [
            'key'        => implode('_', $this->faker->words(5)),
            'name'       => $this->faker->realText(80),
            'link'       => $this->faker->url(),
            'page'       => $this->faker->word(),
            'avatar'     => '/images/avatars/avatar.jpeg',
            'is_enabled' => true,
            'user_id'    => User::factory()->create()->getId(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
