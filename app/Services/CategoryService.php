<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryService
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    /**
     * Get all categories
     */
    public function getAllCategories(): Collection
    {
        return $this->categoryRepository->all();
    }

    /**
     * Get category by ID
     */
    public function getCategory(int $id): ?Category
    {
        return $this->categoryRepository->find($id);
    }

    /**
     * Create category with business logic
     */
    public function createCategory(array $data): Category
    {
        // Business logic: trim whitespace from name
        $data['name'] = trim($data['name']);

        // Business logic: ensure color is valid hex
        if (!isset($data['color'])) {
            $data['color'] = '#00F0FF'; // Default cyberpunk cyan
        }

        return $this->categoryRepository->create($data);
    }

    /**
     * Update category
     */
    public function updateCategory(int $id, array $data): bool
    {
        if (!$this->categoryRepository->find($id)) {
            return false;
        }

        if (isset($data['name'])) {
            $data['name'] = trim($data['name']);
        }

        return $this->categoryRepository->update($id, $data);
    }

    /**
     * Delete category
     */
    public function deleteCategory(int $id): bool
    {
        return $this->categoryRepository->delete($id);
    }
}
