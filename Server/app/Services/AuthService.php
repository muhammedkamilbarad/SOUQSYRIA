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

class AuthService
{
    protected $repository;
    protected $accessTokenExpiresInMinutes;
    protected $refreshTokenExpiresInMinutes;

    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }
    
    // This is just a setter function for setting access and refresh tokens
    public function setTokenExpirationTimes(int $accessTokenMinutes, int $refreshTokenMinutes): void
    {
        $this->accessTokenExpiresInMinutes = $accessTokenMinutes;
        $this->refreshTokenExpiresInMinutes = $refreshTokenMinutes;
    }

    public function registerUser(array $data)
    {
        Log::info('Register section start');
        $user = $this->repository->create($data);
        
        // Generate OTP for email verification
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put('otp_' . $data['email'], $otp, now()->addMinutes(3));

        //@@ TO-DO Send OTP to email
        Log::info('OTP for ' . $data['email'] . ': ' . $otp);

        return [
            'user' => $user
        ];
    }

    public function verifyEmail(string $email, int $otp)
    {
        $storedOtp = Cache::get('otp_' . $email);
        if (!$storedOtp || $storedOtp !== (string)$otp) {
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
        Cache::put('otp_' . $email, $otp, now()->addMinutes(3));

        //@@ TO-DO Send OTP to email
        Log::info('Regenerated OTP for ' . $email . ': ' . $otp);
        
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
}
