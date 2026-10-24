<?php

namespace Database\Seeders;

use App\Models\PartyConfig;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the parents' admin account and the party details.
     */
    public function run(): void
    {
        $admin = config('party.admin');

        if (blank($admin['email']) || blank($admin['password'])) {
            throw new RuntimeException('Set PARTY_ADMIN_EMAIL and PARTY_ADMIN_PASSWORD in .env before seeding.');
        }

        User::query()->updateOrCreate(
            ['email' => $admin['email']],
            ['name' => $admin['name'], 'password' => $admin['password']],
        );

        if (PartyConfig::query()->exists()) {
            return;
        }

        PartyConfig::create([
            'child_name' => 'Lorenzo',
            'age' => 6,
            'starts_at' => '2026-11-14 15:00:00',
            'date_display' => 'Sábado, 14 de Novembro de 2026',
            'time_display' => '15:00 às 19:30',
            'venue_name' => 'Espaço Reino Mágico & Buffet Divertido',
            'venue_address' => 'Rua dos Cogumelos Dourados, 120 - Vila Madalena, São Paulo - SP',
            'google_maps_query' => 'Vila Madalena, Sao Paulo',
            'dress_code' => 'Roupas leves e confortáveis para correr e brincar! Se quiser, venha com a camiseta ou fantasia do seu personagem favorito do Super Mario!',
            'gift_preferences' => [
                'clothingSize' => 'Tamanho 6',
                'shoeSize' => 'Tamanho 28/29',
                'favoriteThemes' => ['Super Mario', 'Lego', 'Hot Wheels', 'Jogos de Tabuleiro / Massinha'],
                'wishlistUrl' => '',
            ],
            'contact_phone' => '+55 (11) 98765-4321',
            'deadline_rsvp' => '05 de Novembro de 2026',
        ]);
    }
}
