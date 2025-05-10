<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\SystemComplaint;
use App\Repositories\BaseRepository;

class SystemComplaintRepository extends BaseRepository
{
    public function __construct(SystemComplaint $model)
    {
        parent::__construct($model);
    }

    public function getPaginated(int $perPage = 5)
    {
        return $this->model->paginate($perPage);
    }
}
