<?php

namespace App\Services;

use App\Repositories\ColorRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ColorService
{
    protected $colorRepository;

    public function __construct(ColorRepository $colorRepository)
    {
        $this->colorRepository = $colorRepository;
    }

    public function getAllColors(): Collection
    {
        return $this->colorRepository->getAll();
    }

    public function getColorById(int $id): ?Model
    {
        try {
            return $this->colorRepository->getById($id);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    public function createColor(array $data): Model
    {
        return $this->colorRepository->create($data);
    }

    public function updateColor(Model $color, array $data): Model
    {
        return $this->colorRepository->update($color, $data);
    }

    public function deleteColor(Model $color)
    {
        $this->colorRepository->delete($color);
    }
}
