<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Feature;
use App\Repositories\BaseRepository;

class FeatureRepository extends BaseRepository
{
    public function __construct(Feature $feature)
    {
        parent::__construct($feature);
    }

    public function getAll(): Collection
    {
        return $this->model->with(['featureGroup'])->get();
    }


}
