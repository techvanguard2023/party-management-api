<?php

namespace Database\Factories;

use App\Enums\RsvpStatus;
use App\Models\Rsvp;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rsvp>
 */
class RsvpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'guest_name' => fake()->name(),
            'phone' => '(11) 9'.fake()->numerify('####-####'),
            'status' => RsvpStatus::Confirmed,
            'adults_count' => fake()->numberBetween(1, 2),
            'kids_count' => fake()->numberBetween(0, 3),
            'favorite_character' => 'Mario',
            'dietary_notes' => null,
            'message' => fake()->sentence(),
        ];
    }

    /**
     * The guest can't make it.
     */
    public function declined(): static
    {
        return $this->state(fn () => [
            'status' => RsvpStatus::Declined,
            'adults_count' => 0,
            'kids_count' => 0,
        ]);
    }
}
