<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Subscribing;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;


class SubscribingRepository extends BaseRepository
{
    public function __construct(Subscribing $model)
    {
        parent::__construct($model);
    }

    // Get all subscriptions with pagination, filters and search
    public function getAllWithFiltersAndPagination(array $filters, int $perPage=15): LengthAwarePaginator
    {
        $query = $this->model->with(['user', 'package']);

        // Filter by package id
        if (isset($filters['package_id'])) {
            $query->where('package_id', $filters['package_id']);
        }

        // Filter by is_active
        if (isset($filters['is_active'])) {
            if ($filters['is_active']) {
                $query->where('expiry_date', '>', Carbon::now())->where('remaining_ads', '>', 0);
            } else {
                $query->where(function ($q) {
                    $q->where('expiry_date', '<=', Carbon::now())
                      ->orWhere('remaining_ads', '<=', 0);
                });
            }
        }

        // Filter by expiry_date range
        if (isset($filters['expiry_date_from'])) {
            $query->where('expiry_date', '>=', $filters['expiry_date_from']);
        }

        if (isset($filters['expiry_date_to'])) {
            $query->where('expiry_date', '<=', $filters['expiry_date_to']);
        }

        // Search by user email
        if (isset($filters['user_email'])) {
            $query->whereHas('user', function ($q) use ($filters) {
                $q->where('email', $filters['user_email']);
            });
        }


        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    // Geting a specific subscribing with user and package relationships.
    public function getByIdWithUserAndPackage(int $id): ?Model
    {
        return $this->model->with(['user', 'package'])->findOrFail($id);
    }

    public function getCurrentActiveSubscription(int $userId): ?Model
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('expiry_date', '>', Carbon::now())
            ->where('remaining_ads', '>', 0)
            ->with(['user', 'package'])
            ->first();
    }
}
