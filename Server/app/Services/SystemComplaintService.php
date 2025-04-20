<?php

namespace App\Services;

use App\Repositories\SystemComplaintRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class SystemComplaintService
{
    protected $systemComplaintRepository;

    public function __construct(SystemComplaintRepository $systemComplaintRepository)
    {
        $this->systemComplaintRepository = $systemComplaintRepository;
    }

    public function getSystemComplaints(): Collection
    {
        return $this->systemComplaintRepository->getAll();
    }

    public function getSystemComplaintById(int $id): ?Model
    {
        try {
            return $this->systemComplaintRepository->getById($id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function createSystemComplaint(array $data): Model
    {
        // Lowercase the email before saving
        if (isset($data['email'])) {
            $data['email'] = Str::lower($data['email']);
        }
        return $this->systemComplaintRepository->create($data);
    }

    public function updateSystemComplaint(Model $color, array $data): Model
    {
        return $this->systemComplaintRepository->update($color, $data);
    }

    public function deleteSystemComplaint(Model $color)
    {
        $this->systemComplaintRepository->delete($color);
    }
}