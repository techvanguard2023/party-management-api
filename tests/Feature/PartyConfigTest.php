<?php

namespace Tests\Feature;

use App\Http\Resources\PartyConfigResource;
use App\Models\PartyConfig;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PartyConfigTest extends TestCase
{
    use RefreshDatabase;

    public function test_anyone_can_read_the_party_details(): void
    {
        PartyConfig::factory()->create(['child_name' => 'Lorenzo', 'age' => 6]);

        $this->getJson('/api/v1/party')
            ->assertOk()
            ->assertJsonPath('data.childName', 'Lorenzo')
            ->assertJsonPath('data.age', 6)
            ->assertJsonPath('data.dateStr', '2026-11-14T15:00:00')
            ->assertJsonPath('data.giftPreferences.clothingSize', 'Tamanho 6');
    }

    public function test_it_returns_404_before_the_party_is_seeded(): void
    {
        $this->getJson('/api/v1/party')->assertNotFound();
    }

    public function test_the_admin_updates_the_party(): void
    {
        Sanctum::actingAs(User::factory()->create());
        PartyConfig::factory()->create();

        $payload = (new PartyConfigResource(PartyConfig::current()))->resolve();
        $payload['childName'] = 'Lorenzo';
        $payload['dateStr'] = '2026-11-21T16:00:00';
        $payload['giftPreferences']['favoriteThemes'] = ['Mario', 'Lego'];

        $this->putJson('/api/v1/party', $payload)
            ->assertOk()
            ->assertJsonPath('data.childName', 'Lorenzo')
            ->assertJsonPath('data.dateStr', '2026-11-21T16:00:00')
            ->assertJsonPath('data.giftPreferences.favoriteThemes', ['Mario', 'Lego']);

        $this->assertSame('Lorenzo', PartyConfig::current()->child_name);
    }

    public function test_it_validates_the_party_update(): void
    {
        Sanctum::actingAs(User::factory()->create());
        PartyConfig::factory()->create();

        $this->putJson('/api/v1/party', ['childName' => ''])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['childName', 'age', 'dateStr', 'giftPreferences']);
    }
}
