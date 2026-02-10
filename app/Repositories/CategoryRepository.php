<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all(int $userId): Collection
    {
        return Category::where('user_id', $userId)
                       ->with('tasks')
                       ->get();
    }

    public function find(int $id): ?Category
    {
        return Category::with('tasks')->find($id);
    }

    public function create(array $data): Category
    {
        // Ensure user_id is set
        if (!isset($data['user_id'])) {
            throw new \Exception('User ID is required');
        }

        return Category::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $category = Category::find($id);
        if ($category) {
            return $category->update($data);
        }
        return false;
    }

    public function delete(int $id): bool
    {
        $category = Category::find($id);
        if ($category) {
            return $category->delete();
        }
        return false;
    }
}
