<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\PopularQuestion;
use App\Repositories\BaseRepository;

class PopularQuestionRepository extends BaseRepository
{
    public function __construct(PopularQuestion $popularQuestion)
    {
        parent::__construct($popularQuestion);
    }

    public function getAllActiveQuestions(): Collection
    {
        return $this->model->where('status', true)->get();
    }
}
