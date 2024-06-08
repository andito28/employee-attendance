<?php

namespace Database\Factories\Employee;

use App\Services\Number\Generator\EmployeeNumber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'number' => EmployeeNumber::generate(),
            'companyOfficeId' => $this->faker->randomElement([1, 2]),
            'departmentId' => $this->faker->randomElement([1, 2]),
            'photo' => 'p.jpg',
            'statusId' => 1
        ];
    }
}
