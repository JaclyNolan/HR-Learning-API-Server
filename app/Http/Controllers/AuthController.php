<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $email = $request->input('email');
            $password = $request->input('password');
            if (!Auth::attempt(['email' => $email, 'password' => $password])) {
                throw new BadRequestException;
            }
            $user = Auth::user();
            /** @var User $user */
            $token = $user->createToken('main')->plainTextToken;

            return response()->json([
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role->name,
                ],
                'token' => $token,
            ], 200);
        } catch (BadRequestException) {
            return response()->json(['message' => 'Invalid email or password'], 400);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    public function checkToken(Request $request)
    {
        //Get PersonalAccessToken model
        /** @var User $user */
        $user = $request->user();

        // The token is valid and not expired
        return response()->json([
            'message' => 'Token is valid',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role->name,
            ]
        ], 200);
    }

    /**
     * logout is a function that soft delete the current access token
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $currentToken = $user->currentAccessToken();
        /** @var PersonalAccessToken $currentToken */
        $currentToken->delete();
        return response()->json('', 204);
    }
}
