<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\VehicleModel;


class VehicleModelRepository extends BaseRepository
{
    public function __construct(VehicleModel $model)
    {
        parent::__construct($model);
    }
}
