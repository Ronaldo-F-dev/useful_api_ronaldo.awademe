<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                "name" => "required|string",
                "email" => "required|string|email|unique:users,email",
                "password" => "required|string"
            ]);
            $user = User::create($validated);
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "created_at" => $user->created_at,
            ],201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        }

    }
}
