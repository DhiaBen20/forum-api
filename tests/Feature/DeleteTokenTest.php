<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $this->assertCount(1, $user->tokens);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/tokens/current');

        $user->refresh();

        $this->assertCount(0, $user->tokens);
    }

    public function test_guest_cannot_logout(): void
    {
        $this->deleteJson('/api/tokens/current')->assertUnauthorized();
    }
}
