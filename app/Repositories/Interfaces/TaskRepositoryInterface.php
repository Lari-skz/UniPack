<?php

namespace App\Repositories\Interfaces;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    /**
     * Get all tasks for a user
     */
    public function getUserTasks(int $userId): Collection;

    /**
     * Find task by ID
     */
    public function find(int $id): ?Task;

    /**
     * Create new task
     */
    public function create(array $data): Task;

    /**
     * Update task
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete task
     */
    public function delete(int $id): bool;

    /**
     * Get tasks for user filtered by status
     */
    public function getByStatus(int $userId, string $status): Collection;

    /**
     * Get overdue tasks for user
     */
    public function getOverdueTasks(int $userId): Collection;
}
