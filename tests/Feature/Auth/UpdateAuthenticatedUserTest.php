<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateAuthenticatedUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_the_email(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->putJson('/api/me', [
            'email' => 'new-email@example.com',
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.email', 'new-email@example.com');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'new-email@example.com',
        ]);
    }

    public function test_it_updates_the_password(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->putJson('/api/me', [
            'password' => 'new-password123',
            'password_confirmation' => 'new-password123',
        ]);

        $response->assertOk();

        $user->refresh();

        $this->assertTrue(Hash::check('new-password123', $user->password));
    }

    public function test_it_updates_the_image(): void
    {
        $user = User::factory()->create([
            'image' => 'https://example.com/old-image.png',
        ]);

        Sanctum::actingAs($user);

        $response = $this->putJson('/api/me', [
            'image' => 'https://example.com/new-image.png',
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.image', 'https://example.com/new-image.png');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'image' => 'https://example.com/new-image.png',
        ]);
    }

    public function test_it_requires_unique_email(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        Sanctum::actingAs($user);

        $response = $this->putJson('/api/me', [
            'email' => $otherUser->email,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('email');
    }

    public function test_it_does_not_allow_changing_the_name(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->putJson('/api/me', [
            'name' => 'New Name',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('name');
    }

    public function test_it_requires_at_least_one_field(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->putJson('/api/me', []);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('email');
    }
}
