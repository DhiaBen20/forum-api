<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;

class DeleteTokenController extends Controller
{
    public function __invoke(#[CurrentUser()] User $user): JsonResponse
    {
        $user->currentAccessToken()->delete();

        return response()->json([], 204);
    }
}
