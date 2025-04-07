<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;  // Add this if you're hashing passwords
use Illuminate\Support\Facades\Log;  // Add this
use Illuminate\Support\Str;
use App\Models\User;
use App\Services\SubscribingService;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Jobs\SendOtpEmailJob;
use App\Jobs\SendPasswordResetEmailJob;

class AuthService
{
    protected $repository;
    protected $accessTokenExpiresInMinutes;
    protected $refreshTokenExpiresInMinutes;
    protected $subscribingService;
    protected $otpExpirationsTime;

    public function __construct(AuthRepository $repository, SubscribingService $subscribingService)
    {
        $this->repository = $repository;
        $this->subscribingService = $subscribingService;
    }
    
    // This is just a setter function for setting access and refresh tokens
    public function setTokenExpirationTimes(int $accessTokenMinutes, int $refreshTokenMinutes): void
    {
        $this->accessTokenExpiresInMinutes = $accessTokenMinutes;
        $this->refreshTokenExpiresInMinutes = $refreshTokenMinutes;
    }

    // This is just a setter function for OTP Expiration time
    public function setOtpExpirationTime(int $otpExpirationsTime=3): void
    {
        $this->otpExpirationsTime = $otpExpirationsTime;
    }

    public function registerUser(array $data)
    {
        Log::info('Register section start');
        $user = $this->repository->create($data);
        
        // Generate OTP for email verification
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put('otp_' . $data['email'], $otp, now()->addMinutes($this->otpExpirationsTime));

        //@@ TO-DO Send OTP to email
        Log::info('OTP for ' . $data['email'] . ': ' . $otp);

        // Send OTP email asynchronously
        SendOtpEmailJob::dispatch(
            $data['email'],
            $otp,
            $data['name'],
            $this->otpExpirationsTime
        );
        return [
            'user' => $user
        ];
    }

    public function verifyEmail(string $email, string $otp)
    {
        $storedOtp = Cache::get('otp_' . $email);
        if (!$storedOtp || $storedOtp !== $otp) {
            return false;
        }
        
        $user = $this->repository->findByEmail($email);
        if (!$user) {
            return false;
        }
        
        $this->repository->verifyEmail($email);
        Cache::forget('otp_' . $email);

        // Delete existing tokens
        $this->repository->deleteAllUserTokens($user);

        // Generate new tokens
        $accessToken = $user->createToken(
            'access_token', 
            [], 
            now()->addMinutes($this->accessTokenExpiresInMinutes)
        )->plainTextToken;
        
        $refreshToken = $this->repository->createRefreshToken(
            $user->id, 
            $this->refreshTokenExpiresInMinutes
        );
        
        // create free subscription for new user
        $data = [
            "user_id" => $user->id,
            "package_id" => 1, // free package for new users
        ];
        $this->subscribingService->createSubscribing($data);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];
    }

    public function regenerateOtp(string $email)
    {
        $user = $this->repository->findByEmail($email);
        if (!$user) {
            return false;
        }
        
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put('otp_' . $email, $otp, now()->addMinutes($this->otpExpirationsTime));

        //@@ TO-DO Send OTP to email
        Log::info('Regenerated OTP for ' . $email . ': ' . $otp);


        // Send OTP email asynchronously
        SendOtpEmailJob::dispatch(
            $user['email'],
            $otp,
            $user['name'],
            $this->otpExpirationsTime
        )->onQueue('otp'); 
        
        return $otp;
    }

    public function loginUser(string $login_input, string $password)
    {
        Log::info('Login section start');
        $user = $this->repository->findTheUserByEmailOrByPhone($login_input);

        if (!$user || !Hash::check($password, $user->password)) {
            return false;
        }

        if (!$user->is_verified) {
            return null;
        }

        // Delete existing tokens
        $this->repository->deleteAllUserTokens($user);

        // Generate new access token
        $accessToken = $user->createToken('access_token', [], now()->addMinutes($this->accessTokenExpiresInMinutes))->plainTextToken;
        Log::info('Access token for ' . $user['email'] . ': ' . $accessToken);

        // Generate new refresh token
        $refreshToken = $this->repository->createRefreshToken(
            $user->id, 
            $this->refreshTokenExpiresInMinutes
        );
        Log::info('Refresh token for ' . $user['email'] . ': ' . $refreshToken);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];
    }

    public function loginAdminUser(string $login_input, string $password)
    {
        Log::info('Login section start');
        $user = $this->repository->findTheUserByEmailOrByPhone($login_input);

        if (!$user || !Hash::check($password, $user->password)) {
            return false;
        }

        if (!$user->is_verified) {
            return null;
        }

        // Check if user has admin privileges
        if ($user->role_id === 1)
        {
            return false;
        }

        // Delete existing tokens
        $this->repository->deleteAllUserTokens($user);

        // Generate new access token
        $accessToken = $user->createToken('access_token', [], now()->addMinutes($this->accessTokenExpiresInMinutes))->plainTextToken;
        Log::info('Access token for ' . $user['email'] . ': ' . $accessToken);

        // Generate new refresh token
        $refreshToken = $this->repository->createRefreshToken(
            $user->id, 
            $this->refreshTokenExpiresInMinutes
        );
        Log::info('Refresh token for ' . $user['email'] . ': ' . $refreshToken);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];
    }

    public function refreshToken(string $refreshToken)
    {
        Log::info('Refresh token section start');

        // find the stored refresh token
        $storedToken = $this->repository->findRefreshToken($refreshToken);
        
        if (!$storedToken || $storedToken->expires_at < now()) {
            return false;
        }

        $user = $storedToken->user;
        if (!$user) {
            return false;
        }

        // Delete existing tokens
        $this->repository->deleteAllUserTokens($user);

        // Generate a new access token
        $accessToken = $user->createToken('access_token', [], now()->addMinutes($this->accessTokenExpiresInMinutes))->plainTextToken;
        Log::info('Access token for ' . $user['email'] . ': ' . $accessToken);
        
        // Generate a new refresh token
        $newRefreshToken = $this->repository->createRefreshToken(
            $user->id, 
            $this->refreshTokenExpiresInMinutes
        );
        Log::info('Refresh token for ' . $user['email'] . ': ' . $newRefreshToken);

        $permissions = $user->permissions;

        return [
            'token' => $accessToken,
            'refresh_token' => $newRefreshToken,
            'permissions' => $permissions
        ];
    }

    public function logoutUser(User $user)
    {
        // Delete access tokens
        $user->tokens()->delete();
        // Delete refresh tokens
        $this->repository->deleteAllRefreshTokens($user->id);
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        // Verify current password
        if (!Hash::check($currentPassword, $user->password))
        {
            return false;
        }

        // Update password
        $this->repository->updatePassword($user->id, $newPassword);

        return true;
    }

    public function sendPasswordResetLink(string $email): void
    {
        $token = $this->generateResetToken($email);

        // Send email with reset link
        // here sending reset link with email
        $user = $this->repository->findByEmail($email);

        // Define the reset URL with the token
        $resetUrl = url('/api/reset-password?token=' . $token);
        
        // Logging the reset link
        Log::info('Password reset link for ' . $email . ': ' . $resetUrl);

        // Dispatching the job to send the password reset email
        SendPasswordResetEmailJob::dispatch(
            $user->email,
            $user->name,
            $resetUrl,
            config('auth.passwords.users.expire') // typically 60 minutes
        );
    }

    public function generateResetToken(string $email): string
    {
        // Create a token and store it in the password_resets table
        $token = Str::random(60);

        $this->repository->storeResetToken($email, $token);

        return $token;
    }

    public function resetPassword(string $email, string $token, string $newPassword): bool
    {
        // Validate token
        if (!$this->repository->validateResetToken($email, $token))
        {
            return false;
        }
        
        // Update password
        $user = $this->repository->findByEmail($email);
        $this->repository->updatePassword($user->id, $newPassword);

        // Delete the used token
        $this->repository->deleteResetToken($email);

        return true;
    }

    // Check if the user is authenticated
    public function checkAuth($request): bool
    { 
        // First check if there's an authenticated user on the request
        $user = $request->user();
        if ($user) {
            return true;
        }
        
        // If no user found via request, try to get the token from cookie
        $accessToken = $request->cookie('access_token');
        if (!$accessToken) {
            return false;
        }
        
        try {
            // Parse the token to get its ID and hash
            $tokenParts = explode('|', $accessToken, 2);
            
            // If token format is invalid
            if (count($tokenParts) !== 2) {
                return false;
            }
            
            $tokenId = $tokenParts[0];
            
            // Find the token in the database
            $tokenModel = \Laravel\Sanctum\PersonalAccessToken::find($tokenId);
            
            if (!$tokenModel) {
                return false;
            }
            
            // Check if token has expired
            if ($tokenModel->expires_at && now()->gt($tokenModel->expires_at)) {
                return false;
            }
            
            // Get the associated user
            $user = $tokenModel->tokenable;
            if (!$user) {
                return false;
            }
            
            if (!$user->is_verified) {
                return false;
            }
            
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
