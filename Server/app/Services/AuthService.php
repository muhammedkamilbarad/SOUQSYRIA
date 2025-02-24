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

    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }

    public function registerUser(array $data)
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put('otp_' . $data['email'], $otp, now()->addMinutes(3));

        //@@ TO-DO Send OTP to email

        Log::info('OTP for ' . $data['email'] . ': ' . $otp);
        return $this->repository->create($data);
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

        $permissions = $user->permissions;
        return [
            'token' => $user->createToken('auth_token')->plainTextToken,
            'permissions' => $permissions,
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
        $user = $this->repository->findTheUserByEmailOrByPhone($login_input);

        if (!$user || !Hash::check($password, $user->password)) {
            return false;
        }

        if (!$user->is_verified) {
            return null;
        }
        $permissions = $user->permissions;

        return [
            'token' => $user->createToken('auth_token')->plainTextToken,
            'permissions' => $permissions
        ];
    }
    public function logoutUser(User $user)
    {
        $user->tokens()->delete();
    }
}
