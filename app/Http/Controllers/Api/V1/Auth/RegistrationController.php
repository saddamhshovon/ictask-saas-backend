<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegistrationRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function __invoke(RegistrationRequest $request)
    {
        $validated = $request->validated();

        try {
            $user = User::create([...$validated, 'password' => Hash::make($validated['password'])]);

            $token = $user->createToken(
                name: $user->email,
                expiresAt: null
            )->plainTextToken;

            return response()->json(
                [
                    'message' => 'Registration successful.',
                    'data' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'token' => $token,
                    ],
                ],
                Response::HTTP_CREATED
            );
        } catch (\Throwable $th) {

            return response()->json(
                [
                    'message' => $th->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
