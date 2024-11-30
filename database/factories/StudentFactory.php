<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
final class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => fake()->unique()->email(),
            'name_util' => fake()->userName(),
            'telephone' => fake()->numerify("#9 ### ###"),
            'birthday' => fake()
                ->dateTimeBetween('-22 years', '-20 years')
                ->format('Y-m-d'),
            'gender' => fake()->numberBetween(['M', 'F'])
        ];
    }
}
