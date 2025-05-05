<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\BaseRepository;
use App\Models\SubscriptionRequest;


class SubscriptionRequestRepository extends BaseRepository
{
    public function __construct(SubscriptionRequest $model)
    {
        parent::__construct($model);
    }

    public function getAllWithRelations(): Collection
    {
        return $this->model->with(['user', 'package'])->get();
    }

    public function checkPendingByUserId(int $userId)
    {
        return $this->model->where('user_id', $userId)->where('status', 'pending')
        ->first();
    }
}
