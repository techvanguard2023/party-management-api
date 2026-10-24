<?php

namespace Tests\Feature;

use App\Models\Rsvp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RsvpTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, mixed>
     */
    private function payload(array $overrides = []): array
    {
        return array_merge([
            'guestName' => 'Família Silva',
            'phone' => '(11) 98888-1122',
            'status' => 'confirmed',
            'adultsCount' => 2,
            'kidsCount' => 1,
            'favoriteCharacter' => 'Mario',
            'dietaryNotes' => 'Sem restrições',
            'message' => 'Parabéns!',
        ], $overrides);
    }

    public function test_a_guest_can_rsvp_without_logging_in(): void
    {
        $this->postJson('/api/v1/rsvps', $this->payload())
            ->assertCreated()
            ->assertJsonPath('data.guestName', 'Família Silva')
            ->assertJsonPath('data.adultsCount', 2)
            ->assertJsonPath('data.status', 'confirmed');

        $this->assertDatabaseHas('rsvps', ['guest_name' => 'Família Silva', 'kids_count' => 1]);
    }

    public function test_it_validates_the_rsvp(): void
    {
        $this->postJson('/api/v1/rsvps', $this->payload([
            'guestName' => '',
            'phone' => 'abc',
            'status' => 'maybe',
            'adultsCount' => 99,
        ]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['guestName', 'phone', 'status', 'adultsCount']);
    }

    public function test_guests_must_leave_a_phone(): void
    {
        $this->postJson('/api/v1/rsvps', $this->payload(['phone' => null]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('phone');
    }

    public function test_the_admin_can_add_a_guest_without_a_phone(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/v1/rsvps', $this->payload(['phone' => null]))
            ->assertCreated()
            ->assertJsonPath('data.phone', '');
    }

    public function test_optional_fields_come_back_as_empty_strings(): void
    {
        $this->postJson('/api/v1/rsvps', $this->payload([
            'status' => 'declined',
            'adultsCount' => 0,
            'kidsCount' => 0,
            'favoriteCharacter' => null,
            'dietaryNotes' => null,
            'message' => null,
        ]))
            ->assertCreated()
            ->assertJsonPath('data.message', '')
            ->assertJsonPath('data.dietaryNotes', '');
    }

    public function test_guests_cannot_read_the_guest_list(): void
    {
        Rsvp::factory()->create();

        $this->getJson('/api/v1/rsvps')->assertUnauthorized();
    }

    public function test_the_admin_lists_rsvps_newest_first(): void
    {
        Sanctum::actingAs(User::factory()->create());
        Rsvp::factory()->create(['guest_name' => 'Antigo', 'created_at' => now()->subDay()]);
        Rsvp::factory()->declined()->create(['guest_name' => 'Novo']);

        $this->getJson('/api/v1/rsvps')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.guestName', 'Novo')
            ->assertJsonPath('data.0.status', 'declined');
    }

    public function test_the_admin_deletes_an_rsvp(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $rsvp = Rsvp::factory()->create();

        $this->deleteJson("/api/v1/rsvps/{$rsvp->id}")->assertNoContent();

        $this->assertModelMissing($rsvp);
    }
}
