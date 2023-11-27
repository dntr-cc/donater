<?php

namespace Database\Factories;

use App\Models\Donate;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DonateFactory extends Factory
{
    protected $model = Donate::class;

    public function definition(): array
    {
        $i = rand() % 3;
        return [
            'user_id'      => User::factory()->create()->getId(),
            'volunteer_id' => $i + 1,
            'amount'       => $this->faker->randomFloat(),
            'uniq_hash'    => uniqid('', true),
            'validated_at' => rand() % 2 === 0 ? Carbon::now() : null,
            'created_at'   => Carbon::now()->subDays(rand(0, 14)),
            'updated_at'   => Carbon::now(),
        ];
    }

    /**
     * @param int $userId
     * @return DonateFactory
     */
    public function forUser(int $userId): DonateFactory
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $userId,
        ]);
    }

    /**
     * @param Volunteer $volunteer
     * @return DonateFactory
     */
    public function withVolunteer(Volunteer $volunteer): DonateFactory
    {
        return $this->state(fn (array $attributes) => [
            'volunteer_id' => $volunteer->getId(),
        ]);
    }

    /**
     * @param User $user
     * @return DonateFactory
     */
    public function withUser(User $user): DonateFactory
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->getId(),
        ]);
    }
}
