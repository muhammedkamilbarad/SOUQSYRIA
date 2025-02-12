<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyAccountRequest;
use App\Http\Requests\ResendOTPRequest;
use Illuminate\Http\Request;

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
        $token = $this->service->verifyEmail($request->email, $request->otp);
        if(!$token){
            return response()->json(['error' => 'Invalid OTP or Emaill'], 400);
        }
        return response()->json([
            'message' => 'Email verified successfully',
            'token' => $token
        ], 200);
    }

    public function resendteOtp(ResendOTPRequest $request): JsonResponse
    {
        $newOtp = $this->service->regenerateOtp($request->email);
        if (!$newOtp) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json(['message' => 'Neew OTP has been sent to your email'], 200);
    }

}
