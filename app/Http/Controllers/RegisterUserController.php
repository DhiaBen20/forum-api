<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class RegisterUserController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'email', 'unique:users,email'],
            'password' => ['required', Password::min(6)->letters()->numbers()],
        ]);

        User::create($data);

        return response()->json([], 101);
    }
}
