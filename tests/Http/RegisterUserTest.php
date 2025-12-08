<?php

namespace Tests\Http;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    #[DataProvider('invalidData')]
    public function test_validation_rules(array $data, array $errors): void
    {
        if (isset($data['email']) && $data['email'] === 'exists@example.com') {
            User::factory()->create(['email' => 'exists@example.com']);
        }

        $this->postJson('/api/users', $data)
            ->assertJsonValidationErrors($errors);
    }

    public static function invalidData(): array
    {
        return [
            'missing name' => [
                ['email' => 'test@example.com', 'password' => 'password123'],
                ['name'],
            ],
            'missing email' => [
                ['name' => 'Test User', 'password' => 'password123'],
                ['email'],
            ],
            'invalid email' => [
                ['name' => 'Test User', 'email' => 'not-an-email', 'password' => 'password123'],
                ['email'],
            ],
            'email taken' => [
                ['name' => 'Test User', 'email' => 'exists@example.com', 'password' => 'password123'],
                ['email'],
            ],
            'missing password' => [
                ['name' => 'Test User', 'email' => 'new@example.com'],
                ['password'],
            ],
            'short password' => [
                ['name' => 'Test User', 'email' => 'new@example.com', 'password' => 'pass'],
                ['password'],
            ],
        ];
    }
}
