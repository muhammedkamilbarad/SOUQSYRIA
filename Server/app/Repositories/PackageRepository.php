<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Package;


class PackageRepository extends BaseRepository
{
    public function __construct(Package $model)
    {
        parent::__construct($model);
    }
}
