<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Complaint;
use App\Repositories\BaseRepository;

class ComplaintRepository extends BaseRepository
{
    public function __construct(Complaint $model)
    {
        parent::__construct($model);
    }

    public function createComplaint(array $data)
    {
        // Check if user_id and advs_id exist in the data array before querying
        if (isset($data['user_id']) && isset($data['advs_id'])) {
            $existingComplaint = $this->model
                ->where('user_id', $data['user_id'])
                ->where('advs_id', $data['advs_id'])
                ->first();

            if ($existingComplaint) {
                return $existingComplaint;
            }
        }
        
        // Create new complaint with provided data
        return $this->model->create([
            'content' => $data['content'],
            'user_id' => $data['user_id'],
            'advs_id' => $data['advs_id'] ?? null,
        ]);
    }

    public function getAllComplaintsForUser(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function getComplaintsForAdvertisement(int $advertisementId): Collection
    {
        return $this->model->where('advs_id', $advertisementId)->get();
    }

    public function checkComplaintExistence(int $userId, int $advsId)
    {
        return $this->model->where('user_id', $userId)->where('advs_id', $advsId)->exists();
    }

    public function getPaginated(int $perPage = 5, $adv_id = null)
    {
        if(isset($adv_id)){
            return $this->model->where('advs_id', $adv_id)->paginate($perPage);
        }
        return $this->model->paginate($perPage);
    }

}