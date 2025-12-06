<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class CreateTokenController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', Password::min(6)->numbers()->letters()],
        ]);

        $key = 'check-user:'.$request->ip().':'.$request->string('email');

        $user = User::query()->where('email', $request->input('email'))->first();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages(
                ['email' => 'Too many attempts']
            );
        }

        if (! $user || ! Hash::check($request->string('password')->value(), $user->password)) {
            RateLimiter::hit($key);

            throw ValidationException::withMessages(
                ['email' => 'The provided credentials are incorrect.']
            );
        }

        $token = $user->createToken('name');

        return response()->json(['token' => $token->plainTextToken], 201);
    }
}
