<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Handle user login request and generate an API token.
     *
     * Steps:
     *  1. Validate incoming request (email & password are required).
     *  2. Find user by email.
     *  3. Verify provided password against the stored hash.
     *  4. If valid, create a Sanctum token and return it along with user info.
     *  5. If invalid, throw a validation exception with a generic error.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            // 1️⃣ Validate input
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // 2️⃣ Attempt to find the user by email
            $user = User::where('email', $request->email)->first();

            // 3️⃣ Verify credentials (user exists & password matches hash)
            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            // 4️⃣ Create a new API token for the user (via Laravel Sanctum)
            $token = $user->createToken('auth_token')->plainTextToken;

            // 5️⃣ Return success response with token & user data
            return response()->json([
                'access_token' => $token,
                'token_type'   => 'Bearer',
                'user'         => $user
            ]);
        } catch (\Exception $e) {
            // ❌ Any unexpected error will be caught here
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle user logout request by revoking the current API token.
     *
     * Steps:
     *  1. Delete the currently used access token from the database.
     *  2. Respond with a success message.
     *  3. If token deletion fails, return an error response.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            // 1️⃣ Delete the current user's active token
            $request->user()->currentAccessToken()->delete();

            // 2️⃣ Return a success message
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        } catch (\Exception $e) {
            // ❌ Catch and return failure response
            return response()->json([
                'message' => 'Logout failed',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
