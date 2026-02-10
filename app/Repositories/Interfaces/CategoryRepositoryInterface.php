<?php

namespace App\Repositories\Interfaces;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    /**
     * Get all categories
     */
    public function all(): Collection;

    /**
     * Find category by ID
     */
    public function find(int $id): ?Category;

    /**
     * Create new category
     */
    public function create(array $data): Category;

    /**
     * Update category
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete category
     */
    public function delete(int $id): bool;
}
