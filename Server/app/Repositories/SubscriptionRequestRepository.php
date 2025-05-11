<?php
namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\BaseRepository;
use App\Models\SubscriptionRequest;
use Illuminate\Pagination\LengthAwarePaginator;


class SubscriptionRequestRepository extends BaseRepository
{
    public function __construct(SubscriptionRequest $model)
    {
        parent::__construct($model);
    }

    public function getAllWithFiltersAndPagination(array $filters, int $perPage=15): LengthAwarePaginator
    {

        $query = $this->model->with(['user', 'package']);
        
        // Apply status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        // Apply package_id filter
        if (!empty($filters['package_id'])) {
            $query->where('package_id', $filters['package_id']);
        }
        
        // Apply created_at date range filter
        if (!empty($filters['created_at_from'])) {
            $query->where('created_at', '>=', $filters['created_at_from']);
        }
        
        if (!empty($filters['created_at_to'])) {
            $query->where('created_at', '<=', $filters['created_at_to'] . ' 23:59:59');
        }
        
        // Apply processed_at date range filter
        if (!empty($filters['processed_at_from'])) {
            $query->where('processed_at', '>=', $filters['processed_at_from']);
        }
        
        if (!empty($filters['processed_at_to'])) {
            $query->where('processed_at', '<=', $filters['processed_at_to'] . ' 23:59:59');
        }
        
        // Apply user email search
        if (!empty($filters['user_email'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('email', $filters['user_email']);
            });
        }
        
        // Order by created_at desc (newest first)
        $query->orderBy('created_at', 'desc');
        
        return $query->paginate($perPage);
    }

    public function checkPendingByUserId(int $userId)
    {
        return $this->model->where('user_id', $userId)->where('status', 'pending')
        ->first();
    }
}
