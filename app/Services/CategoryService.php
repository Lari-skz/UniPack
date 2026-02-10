<?php

namespace App\Services;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAllCategories(int $userId): Collection
    {
        return $this->categoryRepository->all($userId);
    }

    public function getCategory(int $id)
    {
        return $this->categoryRepository->find($id);
    }

    public function createCategory(int $userId, array $data)
    {
        // Add user_id to the data
        $data['user_id'] = $userId;

        return $this->categoryRepository->create($data);
    }

    public function updateCategory(int $id, array $data): bool
    {
        return $this->categoryRepository->update($id, $data);
    }

    public function deleteCategory(int $id): bool
    {
        return $this->categoryRepository->delete($id);
    }
}
