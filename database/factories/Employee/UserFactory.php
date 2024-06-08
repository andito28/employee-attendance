<?php

namespace Database\Factories\Employee;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employeeId' => null,
            'email' => $this->faker->unique()->safeEmail,
            'password' =>Hash::make('12345678'),
            'roleId' => 2
        ];
    }
}
