<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    public function sanctumSignIn(?User $user = null): User
    {
        $user ??= User::factory()->create();

        return Sanctum::actingAs($user);
    }
}
