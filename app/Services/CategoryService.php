<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\CategoryRepository;


class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories(): Collection
    {
        return $this->categoryRepository->getAll();
    }

    public function getCategoryById(int $id): ?Model
    {
        try
        {
            return $this->categoryRepository->getById($id);
        }
        catch (ModelNotFoundException $e)
        {
            return null;
        }
    }

    public function createCategory(array $data): Model
    {
        return $this->categoryRepository->create($data);
    }

    public function updateCategory(Model $category, array $data): Model
    {
        return $this->categoryRepository->update($category, $data);
    }

    public function deleteCategory(Model $category)
    {
        $this->categoryRepository->delete($category);
    }
}