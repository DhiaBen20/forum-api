<?php

namespace Tests\Http;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CreateTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_token(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/tokens/create', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['token']);
    }

    public function test_user_must_enter_valid_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/tokens/create', [
            'email' => $user->email,
            'password' => 'wrong-password123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonPath('errors.email.0', 'The provided credentials are incorrect.');
    }

    public function test_user_is_rate_limited(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/tokens/create', [
                'email' => $user->email,
                'password' => 'wrong-password123',
            ]);
        }

        $response = $this->postJson('/api/tokens/create', [
            'email' => $user->email,
            'password' => 'wrong-password123',
        ]);

        $response->assertStatus(429);
        $response->assertJsonPath('errors.email.0', 'Too many attempts');
    }

    #[DataProvider('invalidData')]
    public function test_validation_fails_for_invalid_data(array $data, array $errors): void
    {
        $this->postJson('/api/tokens/create', $data)
            ->assertStatus(422)
            ->assertJsonValidationErrors($errors);
    }

    public static function invalidData(): array
    {
        return [
            'missing email' => [
                ['password' => 'password123'],
                ['email'],
            ],
            'invalid email' => [
                ['email' => 'not-an-email', 'password' => 'password123'],
                ['email'],
            ],
            'missing password' => [
                ['email' => 'test@example.com'],
                ['password'],
            ],
            'short password' => [
                ['email' => 'test@example.com', 'password' => 'pass1'],
                ['password'],
            ],
            'password no numbers' => [
                ['email' => 'test@example.com', 'password' => 'password'],
                ['password'],
            ],
            'password no letters' => [
                ['email' => 'test@example.com', 'password' => '123456'],
                ['password'],
            ],
        ];
    }
}
