<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\RefreshToken;

class AuthRepository extends BaseRepository
{

    protected $refreshTokenModel;

    protected $accessTokenExpiresInMinutes;
    protected $refreshTokenExpiresInMinutes;

    public function __construct(User $model, RefreshToken $refreshTokenModel)
    {
        parent::__construct($model);
        $this->refreshTokenModel = $refreshTokenModel;
    }

    public function setTokenExpirationTimes(int $accessTokenExpiresInMinutes, int $refreshTokenExpiresInMinutes): void
    {
        $this->accessTokenExpiresInMinutes = $accessTokenExpiresInMinutes;
        $this->refreshTokenExpiresInMinutes = $refreshTokenExpiresInMinutes;
    }

    // Create both access and refresh tokens for a user
    public function createTokens(int $userId): array
    {
        // Get the user by its id
        $user = $this->model->findOrFail($userId);

        // Generate a new access token using Laravel Sanctum
        $accessToken = $user->createToken(
            'access_token', 
            [], 
            now()->addMinutes($this->accessTokenExpiresInMinutes)
        )->plainTextToken;

        // Generate a refresh token
        $refreshToken = $this->createRefreshToken($userId, $this->refreshTokenExpiresInMinutes);

        // Return both tokens
        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken
        ];
    }

    // Delete all tokens for a user
    public function deleteTokens(int $userId)
    {
        // Get the user by its id
        $user = $this->model->findOrFail($userId);

        // Delete access tokens
        $user->tokens()->delete();

        // Delete refresh tokens
        $this->refreshTokenModel->where('user_id', $userId)->delete();
    }

    // Check if both access and refresh tokens are valid
    public function checkTokensValidations(int $userId): bool
    {
        // Get the user by its id
        $user = $this->model->findOrFail($userId);

        // Check if user has valid access tokens
        $hasValidAccessToken = $user->tokens()->where('expires_at', '>', now())->exists();

        // Check if user has valid refresh tokens
        $hasValidRefreshToken = $this->refreshTokenModel
        ->where('user_id', $userId)
        ->where('expires_at', '>', now())
        ->exists();

        return $hasValidAccessToken && $hasValidRefreshToken;
    }

    // Check if token with cookie is valid
    public function checkTokensWithCookie($request): bool
    {
        $accessToken = $request->cookie('access_token');
        \Log::info('Access Token' . $accessToken);
        if (!$accessToken) {
            return false;
        }

        if (str_starts_with($accessToken, 'Bearer ')) {
            $accessToken = Str::replaceFirst('Bearer ', '', $accessToken);
        }

        // Find token using Sanctum's PersonalAccessToken::findToken()
        $tokenModel = PersonalAccessToken::findToken($accessToken);

        if (!$tokenModel || !$tokenModel->tokenable) {
            return false;
        }

        $user = $tokenModel->tokenable;
        \Log::info('User ==> ' . $user);
        return $this->checkTokensValidations($user->id);
    }

    private function createRefreshToken(int $userId, int $expiresInMinutes): string
    {
        $token = RefreshToken::generateToken();

        // First delete existing tokens for this user
        $this->refreshTokenModel->where('user_id', $userId)->delete();
        
        // Create a new refresh token
        $this->refreshTokenModel->create([
            'user_id' => $userId,
            'token' => RefreshToken::hashToken($token),
            'expires_at' => now()->addMinutes($expiresInMinutes)
        ]);
        
        return $token;
    }

    public function findRefreshToken(string $token): ?RefreshToken
    {
        $hashedToken = RefreshToken::hashToken($token);
        return $this->refreshTokenModel->where('token', $hashedToken)->first();
    }

    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->model->create($data);
        return $user;
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function verifyEmail(string $email): void
    {
        $this->model->where('email', $email)->update([
            'is_verified' => true,
            'email_verified_at' => Now()
        ]);
    }

    public function findTheUserByEmailOrByPhone(string $login_input): ?User
    {
        return $this->model->where('email', $login_input)
                        ->orWhere('phone', $login_input)
                        ->first();
    }

    public function updatePassword(int $userId, string $newPassword): void
    {
        $user = $this->model->findOrFail($userId);
        $user->password = Hash::make($newPassword);
        $user->save();
    }

    public function storeResetToken(string $email, string $token): void
    {
        // Using Laravel's DB facade to work with the password_resets table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );
    }

    public function validateResetToken(string $email, string $token): bool
    {
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if (!$resetRecord) {
            return false;
        }

        // Check if token is valid
        if (!Hash::check($token, $resetRecord->token)) {
            return false;
        }

        // Check if token is expired (e.g., 60 minutes)
        if (Carbon::parse($resetRecord->created_at)->addMinutes(60)->isPast()) {
            // Token expired
            $this->deleteResetToken($email);
            return false;
        }

        return true;
    }

    public function deleteResetToken(string $email): void
    {
        DB::table('password_reset_tokens')->where('email', $email)->delete();
    }
}
