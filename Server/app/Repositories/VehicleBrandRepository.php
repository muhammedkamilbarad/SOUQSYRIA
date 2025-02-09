<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\VehicleBrand;


class VehicleBrandRepository extends BaseRepository
{
    public function __construct(VehicleBrand $model)
    {
        parent::__construct($model);
    }
}
