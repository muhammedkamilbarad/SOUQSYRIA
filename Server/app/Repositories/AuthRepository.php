<?php
namespace App\Repositories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Hash;

class AuthRepository extends BaseRepository
{

    public function __construct(User $model)
    {
        parent::__construct($model);
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
