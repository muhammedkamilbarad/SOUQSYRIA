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

    public function getBrandsByCategory(?int $category_id): Collection
    {
        if ($category_id)
        {
            $brands =  $this->model->where('category_id', $category_id)->get();
        }
        else
        {
            $brands =  $this->model->all();
        }
        return $brands;
    }
}
