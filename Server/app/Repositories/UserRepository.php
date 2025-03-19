<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use App\Repositories\AuthRepository;


class UserRepository extends BaseRepository
{

    protected $authRepository;

    public function __construct(User $model, AuthRepository $authRepository)
    {
        parent::__construct($model);

        $this->authRepository = $authRepository;
    }

    public function create(array $data): Model
    {
        $user = $this->model->create($data);

        // Refresh the user instance to get the latest data (including verified_at)
        $user->refresh();

        // Load the role relationship
        $user->load('role');

        return $user;
    }

    // Geting specific users with his role
    public function getUserWithRole(int $id): User
    {
        return $this->model->with(['role', 'role.permissions'])->findOrFail($id);
    }
    public function findTrashed(int $id): ?Model
    {
        return $this->model::onlyTrashed()->find($id);
    }

    public function getUsersWithFiltersAndSearch(array $filters = [], array $searchTerms = []): Collection
    {
        $query = $this->model->with(['role']);

        // Applying Filters
        $query = $this->applyFilters($query, $filters);

        // Applying Search Terms
        $query = $this->applySearchTerms($query, $searchTerms);

        return $query->get();
    }

    protected function applyFilters($query, array $filters)
    {
        // Ensure soft-deleted records are considered if filtering by deleted_at
        if (!empty($filters['deleted_at_from']) || !empty($filters['deleted_at_to'])) {
            $query->withTrashed(); // Include both deleted and non-deleted records
        }
        
        // Filter by role
        if (!empty($filters['role']))
        {
            $query->whereHas('role', function($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        // Filter by created_at range
        if (!empty($filters['created_at_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_at_from']);
        }
        
        if (!empty($filters['created_at_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_at_to']);
        }
        
        // Filter by updated_at range
        if (!empty($filters['updated_at_from'])) {
            $query->whereDate('updated_at', '>=', $filters['updated_at_from']);
        }
        
        if (!empty($filters['updated_at_to'])) {
            $query->whereDate('updated_at', '<=', $filters['updated_at_to']);
        }

        // Filter by deleted_at range (only if we're working with trashed records)
        if (!empty($filters['deleted_at_from'])) {
            $query->whereDate('deleted_at', '>=', $filters['deleted_at_from']);
        }
        
        if (!empty($filters['deleted_at_to'])) {
            $query->whereDate('deleted_at', '<=', $filters['deleted_at_to']);
        }
        
        return $query;
    }

    protected function applySearchTerms($query, array $searchTerms)
    {
        // General search across multiple fields
        if (!empty($searchTerms['search'])) {
            $searchTerm = $searchTerms['search'];
            if (!empty($searchTerms['search'])) {
                $searchTerm = $searchTerms['search'];
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('email', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('phone', 'LIKE', "%{$searchTerm}%");
                });
            }
        }
        
        return $query;
    }
}