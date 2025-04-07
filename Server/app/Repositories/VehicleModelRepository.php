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

    public function getAll(): Collection
    {
        return $this->model->with('vehicleBrand')->get();
    }

    public function getByBrandId(int $id)
    {
        return $this->model->where('brand_id', $id)->with('vehicleBrand')->get();
    }
}
