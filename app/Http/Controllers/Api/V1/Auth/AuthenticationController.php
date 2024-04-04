<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthenticationController extends Controller
{
    public function store(LoginRequest $request)
    {
        try {
            $user = $request->authenticate();
            $remember = $request->validated('remember') ?? false;

            $token = $user->createToken(
                name: $user->email,
                expiresAt: $remember ? null : now()->addHour()
            )->plainTextToken;

            return response()->json(
                [
                    'message' => 'Login successful.',
                    'data' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'token' => $token,
                    ],
                ],
                Response::HTTP_OK
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

    public function destroy(Request $request)
    {
        try {

            $request->user()->currentAccessToken()->delete();

            return response()->json(
                [
                    'message' => 'Logout successful.',
                ],
                Response::HTTP_OK
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
