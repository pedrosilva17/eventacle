<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contest>
 */
class ContestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $optionCount = fake()->numberBetween(2, 8);
        $options = [];
        for ($i = 0; $i < $optionCount; $i++) {
            array_push($options, fake()->name());
        }

        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'options' => implode('|SEP|', $options),
            'result' => null,
            //'event_id' => Event::factory(),
        ];
    }
}
