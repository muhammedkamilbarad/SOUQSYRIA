<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyAccountRequest;
use App\Http\Requests\ResendOTPRequest;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    protected $service;
    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->service->registerUser($request->validated());
        return response()->json(['message' => 'OTP sent to your email', 'user' => $user], 201);
    }

    public function verifyAccount(VerifyAccountRequest $request): JsonResponse
    {
        $result = $this->service->verifyEmail($request->email, $request->otp);
        if ($result === false) {
            return response()->json(['error' => 'Invalid OTP or email'], 400);
        }
        return response()->json([
            'message' => 'Email verified successfully',
            'token' => $result['token'],
            'permissions' => $result['permissions']
        ], 200);
    }

    public function resendteOtp(ResendOTPRequest $request): JsonResponse
    {
        $newOtp = $this->service->regenerateOtp($request->email);
        if (!$newOtp) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json(['message' => 'New OTP has been sent to your email'], 200);
    }

    public function loginWithEmailOrPhone(LoginRequest $request): JsonResponse
    {
        $result = $this->service->loginUser($request->login_input, $request->password);

        if ($result === false) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        } elseif ($result === null) {
            return response()->json(['error' => 'Email not verified'], 403);
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $result['token'],
            'permissions' => $result['permissions']
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user) {
            $this->service->logoutUser($user);
            return response()->json(['message' => 'Logged out successfully'], 200);
        }
    }
}
