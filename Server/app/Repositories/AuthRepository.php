<?php
namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;
use App\Models\RefreshToken;
use Carbon\Carbon;

class AuthRepository extends BaseRepository
{

    protected $refreshTokenModel;

    public function __construct(User $model, RefreshToken $refreshTokenModel)
    {
        parent::__construct($model);
        $this->refreshTokenModel = $refreshTokenModel;
    }

    public function createRefreshToken(int $userId, int $expiresInMinutes): string
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

    public function deleteAllUserTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    public function deleteRefreshToken(RefreshToken $token): void
    {
        $token->delete();
    }

    public function deleteAllRefreshTokens(int $userId): void
    {
        $this->refreshTokenModel->where('user_id', $userId)->delete();
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
}
