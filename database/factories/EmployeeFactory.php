<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->name(),
            'age' => $this->faker->numberBetween(18, 60),
            'job' => $this->faker->jobTitle(),
            'salary' => $this->faker->numberBetween(1000, 100000),
        ];
    }
}
