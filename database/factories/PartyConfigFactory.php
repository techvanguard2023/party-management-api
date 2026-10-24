<?php

namespace Database\Factories;

use App\Models\PartyConfig;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PartyConfig>
 */
class PartyConfigFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'child_name' => fake()->firstName(),
            'age' => fake()->numberBetween(1, 12),
            'starts_at' => '2026-11-14 15:00:00',
            'date_display' => 'Sábado, 14 de Novembro de 2026',
            'time_display' => '15:00 às 19:30',
            'venue_name' => fake()->company(),
            'venue_address' => fake()->address(),
            'google_maps_query' => fake()->city(),
            'dress_code' => fake()->sentence(),
            'gift_preferences' => [
                'clothingSize' => 'Tamanho 6',
                'shoeSize' => 'Tamanho 28/29',
                'favoriteThemes' => ['Lego'],
                'wishlistUrl' => '',
            ],
            'contact_phone' => '+55 (11) 98765-4321',
            'deadline_rsvp' => '05 de Novembro de 2026',
        ];
    }
}
