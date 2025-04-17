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
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;

class AuthController extends Controller
{
    protected $service;
    // Token expiration times
    protected $accessTokenExpiresInMinutes = 1000; // 1000 minutes default (about 16 hours)
    protected $refreshTokenExpiresInMinutes = 3000; // 3000 minutes default (about 50 hours)
    protected $otpExpirationInMinutes = 3; // 3 minutes

    public function __construct(AuthService $service)
    {
        $this->service = $service;

        $this->service->setTokenExpirationTimes(
            $this->accessTokenExpiresInMinutes,
            $this->refreshTokenExpiresInMinutes
        );

        $this->service->setOtpExpirationTime($this->otpExpirationInMinutes);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->service->registerUser($request->validated());
        
        return response()->json([
            'message' => 'OTP sent to your email', 
            'user' => $result['user']
        ], 200);
    }

    public function verifyAccount(VerifyAccountRequest $request): JsonResponse
    {
        $result = $this->service->verifyEmail($request->email, $request->otp);
        if ($result === false) {
            return response()->json(['error' => '.رقم التحقق غير صالح'], 400);
        }
        
        return response()->json([
            'message' => 'Email verified successfully',
            'access_token' => $result['access_token'],
            'refresh_token' => $result['refresh_token'],
        ], 200)
        ->cookie('access_token', $result['access_token'], $this->accessTokenExpiresInMinutes, '/', null, true, true, false, 'none')
        ->cookie('refresh_token', $result['refresh_token'], $this->refreshTokenExpiresInMinutes, '/', null, true, true, false, 'none');
    }

    public function resendOtp(ResendOTPRequest $request): JsonResponse
    {
        $newOtp = $this->service->regenerateOtp($request->email);
        if (!$newOtp) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        return response()->json(['message' => '.تم ارسال رمز تحقق جديد'], 200);
    }

    public function dashboardLogin(LoginRequest $request): JsonResponse
    {
        $result = $this->service->loginAdminUser($request->login_input, $request->password);

        $failResponse = $this->handleFailLoginResponse($result);
        if ($failResponse) {
            return $failResponse;
        }

        return $this->handleSuccessLoginResponse($result);
    }

    public function loginWithEmailOrPhone(LoginRequest $request): JsonResponse
    {
        $result = $this->service->loginUser($request->login_input, $request->password);

        $failResponse = $this->handleFailLoginResponse($result);
        if ($failResponse) {
            return $failResponse;
        }

        return $this->handleSuccessLoginResponse($result);
    }

    // Handle successful login response with tokens and cookies
    private function handleSuccessLoginResponse(array $result, string $message = 'Login successful'): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'access_token' => $result['access_token'],
            'refresh_token' => $result['refresh_token'],
        ], 200)
        ->cookie('access_token', $result['access_token'], $this->accessTokenExpiresInMinutes, '/', null, true, true, false, 'none')
        ->cookie('refresh_token', $result['refresh_token'], $this->refreshTokenExpiresInMinutes, '/', null, true, true, false, 'none');
    }

    // Handle failed login response with appropriate error message and status code
    private function handleFailLoginResponse($result): ?JsonResponse
    {
        if ($result === false) {
            return response()->json(['error' => '.معلومات تسجيل الدخول غير صحيحة'], 401);
        } elseif ($result === null) {
            return response()->json(['error' => 'هذا الحساب غير مؤكد يرجى تأكيده'], 403);
        }
        return null; // No failure condition met
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
            'access_token' => $result['access_token'],
            'refresh_token' => $result['refresh_token'],
            'permissions' => $result['permissions']
        ], 200)
        ->cookie('access_token', $result['access_token'], $this->accessTokenExpiresInMinutes, '/', null, true, true, false, 'none')
        ->cookie('refresh_token', $result['refresh_token'], $this->refreshTokenExpiresInMinutes, '/', null, true, true, false, 'none');
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user) {
            $this->service->logoutUser($user);
            
            return response()->json(['message' => '.تم تسجيل الخروج بنجاح'], 200)
                ->cookie('access_token', '', -1)
                ->cookie('refresh_token', '', -1);
        }
        
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $result = $this->service->changePassword(
            $request->user(),
            $request->current_password,
            $request->new_password
        );

        if (!$result)
        {
            return response()->json([
                'error' => 'كلمة المرور الحالية غير صحيحة.'
            ], 400);
        }

        return response()->json([
            'message' => 'تم تغيير كلمة المرور بنجاح.'
        ], 200);
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try
        {
            $this->service->sendPasswordResetLink($request->email);

            return response()->json([
                'message' => 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
        
    }

    public function forgotPasswordMobile(ForgotPasswordRequest $request): JsonResponse
    {
        try
        {
            $resetToken = $this->service->returnPasswordResetLink($request->email);

            return response()->json([
                'message' => "Password reset link successfully created",
                "resetToken" => $resetToken
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
        
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $result = $this->service->resetPasswordWithToken(
            $request->token,
            $request->password
        );

        if (!$result) {
            return response()->json([
                'error' => 'رمز غير صالح أو رمز منتهي الصلاحية.'
            ], 400);
        }

        return response()->json([
            'message' => 'تم إعادة تعيين كلمة المرور بنجاح.'
        ], 200);
    }

    public function checkAuth(Request $request): JsonResponse
    {
        $isAuthenticated = $this->service->checkAuth($request);

        return response()->json(['authenticated' => $isAuthenticated], 200);
    }
}
