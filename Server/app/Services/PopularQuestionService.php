<?php

namespace App\Services;

use App\Repositories\PopularQuestionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PopularQuestionService
{
    protected $PopularQuestionsRepository;

    public function __construct(PopularQuestionRepository $PopularQuestionsRepository)
    {
        $this->PopularQuestionsRepository = $PopularQuestionsRepository;
    }

    public function getAllPopularQuestions(): Collection
    {
        return $this->PopularQuestionsRepository->getAll();
    }

    public function getAllActivePopularQuestions(): Collection
    {
        return $this->PopularQuestionsRepository->getAllActiveQuestions();
    }

    public function getPopularQuestionById(int $id): ?Model
    {
        try {
            return $this->PopularQuestionsRepository->getById($id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function createPopularQuestion(array $data): Model
    {
        return $this->PopularQuestionsRepository->create($data);
    }

    public function updatePopularQuestion(Model $popularQuestion, array $data): Model
    {
        return $this->PopularQuestionsRepository->update($popularQuestion, $data);
    }

    public function deletePopularQuestion(Model $popularQuestion)
    {
        $this->PopularQuestionsRepository->delete($popularQuestion);
    }
}