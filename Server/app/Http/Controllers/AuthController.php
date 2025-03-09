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
use App\Http\Requests\RefreshTokenRequest;

class AuthController extends Controller
{
    protected $service;
    // Token expiration times
    protected $accessTokenExpiresInMinutes = 1; // 1 minutes default
    protected $refreshTokenExpiresInMinutes = 30; // 30 minutes default

    public function __construct(AuthService $service)
    {
        $this->service = $service;

        $this->service->setTokenExpirationTimes(
            $this->accessTokenExpiresInMinutes,
            $this->refreshTokenExpiresInMinutes
        );
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->service->registerUser($request->validated());
        
        return response()->json([
            'message' => 'OTP sent to your email', 
            'user' => $result['user'],
            'access_token' => $result['access_token'],
            'refresh_token' => $result['refresh_token']
        ], 200)
        ->cookie('access_token', $result['access_token'], $this->accessTokenExpiresInMinutes, '/', null, true, true, false, 'none')
        ->cookie('refresh_token', $result['refresh_token'], $this->refreshTokenExpiresInMinutes, '/', null, true, true, false, 'none');
    }

    public function verifyAccount(VerifyAccountRequest $request): JsonResponse
    {
        $result = $this->service->verifyEmail($request->email, $request->otp);
        if ($result === false) {
            return response()->json(['error' => 'Invalid OTP or email'], 400);
        }
        
        return response()->json([
            'message' => 'Email verified successfully',
            'permissions' => $result['permissions']
        ], 200)
        ->cookie('access_token', $result['token'], $this->accessTokenExpiresInMinutes, '/', null, true, true, false, 'none')
        ->cookie('refresh_token', $result['refresh_token'], $this->refreshTokenExpiresInMinutes, '/', null, true, true, false, 'none');
    }

    public function resendOtp(ResendOTPRequest $request): JsonResponse
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
            'access_token' => $result['access_token'],
            'refresh_token' => $result['refresh_token'],
        ], 200)
        ->cookie('access_token', $result['access_token'], $this->accessTokenExpiresInMinutes, '/', null, true, true, false, 'none')
        ->cookie('refresh_token', $result['refresh_token'], $this->refreshTokenExpiresInMinutes, '/', null, true, true, false, 'none');
    }

    public function refreshToken(RefreshTokenRequest $request): JsonResponse
    {

        $refreshToken = $request->cookie('refresh_token');
    
        if (!$refreshToken) {
            return response()->json(['status' => 0, 'message' => 'Refresh token is missing'], 401);
        }
        
        $result = $this->service->refreshToken($refreshToken);
        
        if ($result === false) {
            // Clear the invalid cookies
            return response()->json(['status' => 0, 'message' => 'Invalid or expired refresh token'], 401)
                ->cookie('access_token', '', -1)
                ->cookie('refresh_token', '', -1);
        }
        
        return response()->json([
            'message' => 'Token refreshed successfully',
            'access_token' => $result['token'],
            'refresh_token' => $result['refresh_token'],
            'permissions' => $result['permissions']
        ], 200)
        ->cookie('access_token', $result['token'], $this->accessTokenExpiresInMinutes, '/', null, true, true, false, 'none')
        ->cookie('refresh_token', $result['refresh_token'], $this->refreshTokenExpiresInMinutes, '/', null, true, true, false, 'none');
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user) {
            $this->service->logoutUser($user);
            
            return response()->json(['message' => 'Logged out successfully'], 200)
                ->cookie('access_token', '', -1)
                ->cookie('refresh_token', '', -1);
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
