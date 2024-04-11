<?php

namespace Database\Factories;

use App\Models\IsVolunteer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class IsVolunteerFactory extends Factory
{
    protected $model = IsVolunteer::class;

    public function definition(): array
    {
        return [
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
