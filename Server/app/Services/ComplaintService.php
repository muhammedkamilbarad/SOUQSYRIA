<?php

namespace App\Services;

use App\Repositories\ComplaintRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ComplaintService
{
    protected $complaintRepository;

    public function __construct(ComplaintRepository $complaintRepository)
    {
        $this->complaintRepository = $complaintRepository;
    }

    public function addComplaint(array $data): Model
    {
        return $this->complaintRepository->createComplaint($data);
    }

    public function getAllComplaintsForUser(int $userId): Collection
    {
        return $this->complaintRepository->getAllComplaintsForUser($userId);
    }

    public function getComplaintsForAdvertisement(int $advertisementId): Collection
    {
        return $this->complaintRepository->getComplaintsForAdvertisement($advertisementId);
    }

    public function deleteComplaint(Model $complaint)
    {
        $this->complaintRepository->delete($complaint);
    }

    public function getComplaintById(int $id): ?Model
    {
        try {
            return $this->complaintRepository->getById($id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function getAllComplaints(): Collection
    {
        return $this->complaintRepository->getAll();
    }
}