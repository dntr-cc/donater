<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Fundraising;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FundraisingFactory extends Factory
{
    protected $model = Fundraising::class;

    public function definition(): array
    {
        return [
            'key'        => implode('_', $this->faker->words(5)),
            'name'       => $this->faker->realText(80),
            'link'       => $this->faker->url(),
            'page'       => $this->faker->word(),
            'avatar'     => '/images/banners/ava-fund-default.png',
            'is_enabled' => true,
            'user_id'    => User::factory()->create()->getId(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
