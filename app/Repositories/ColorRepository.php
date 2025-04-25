<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Color;
use App\Repositories\BaseRepository;

class ColorRepository extends BaseRepository
{
    public function __construct(Color $color)
    {
        parent::__construct($color);
    }

}
