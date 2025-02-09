<?php

namespace App\Repositories;

//use Illuminate\Database\Eloquent\MarineType;
use Illuminate\Database\Eloquent\Collection;
use App\Models\MarineType;
use App\Repositories\BaseRepository;

class MarineTypeRepository extends BaseRepository
{
    public function __construct(MarineType $marineType)
    {
        parent::__construct($marineType);
    }
}