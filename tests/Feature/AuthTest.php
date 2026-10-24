<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_issues_a_token_for_valid_credentials(): void
    {
        $user = User::factory()->create(['password' => 'segredo123']);

        $this->postJson('/api/v1/login', ['email' => $user->email, 'password' => 'segredo123'])
            ->assertOk()
            ->assertJsonPath('data.user.email', $user->email)
            ->assertJsonStructure(['data' => ['token']]);
    }

    public function test_it_rejects_a_wrong_password(): void
    {
        $user = User::factory()->create(['password' => 'segredo123']);

        $this->postJson('/api/v1/login', ['email' => $user->email, 'password' => 'errada'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    }

    public function test_logout_revokes_the_current_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('admin-panel')->plainTextToken;

        $this->withToken($token)->postJson('/api/v1/logout')->assertNoContent();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_admin_routes_require_a_token(): void
    {
        $this->getJson('/api/v1/rsvps')->assertUnauthorized();
        $this->putJson('/api/v1/party', [])->assertUnauthorized();
        $this->deleteJson('/api/v1/rsvps/abc')->assertUnauthorized();
    }

    public function test_admin_routes_accept_an_authenticated_user(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->getJson('/api/v1/rsvps')->assertOk();
    }
}
