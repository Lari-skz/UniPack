<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    public function __construct(
        protected TaskRepositoryInterface $taskRepository
    ) {}

    /**
     * Get all tasks for authenticated user
     */
    public function getUserTasks(int $userId): Collection
    {
        return $this->taskRepository->getUserTasks($userId);
    }

    /**
     * Get task by ID
     */
    public function getTask(int $id): ?Task
    {
        return $this->taskRepository->find($id);
    }

    /**
     * Create task with business logic
     */
    public function createTask(int $userId, array $data): Task
    {
        // Business logic: set user_id
        $data['user_id'] = $userId;

        // Business logic: trim title
        $data['title'] = trim($data['title']);

        // Business logic: default status to pending
        if (!isset($data['status'])) {
            $data['status'] = 'pending';
        }

        // Business logic: default priority to medium
        if (!isset($data['priority'])) {
            $data['priority'] = 'medium';
        }

        return $this->taskRepository->create($data);
    }

    /**
     * Update task
     */
    public function updateTask(int $id, array $data): bool
    {
        $task = $this->taskRepository->find($id);

        if (!$task) {
            return false;
        }

        if (isset($data['title'])) {
            $data['title'] = trim($data['title']);
        }

        return $this->taskRepository->update($id, $data);
    }

    /**
     * Mark task as completed
     */
    public function completeTask(int $id): bool
    {
        return $this->taskRepository->update($id, ['status' => 'completed']);
    }

    /**
     * Delete task
     */
    public function deleteTask(int $id): bool
    {
        return $this->taskRepository->delete($id);
    }

    /**
     * Get tasks by status
     */
    public function getTasksByStatus(int $userId, string $status): Collection
    {
        return $this->taskRepository->getByStatus($userId, $status);
    }

    /**
     * Get overdue tasks
     */
    public function getOverdueTasks(int $userId): Collection
    {
        return $this->taskRepository->getOverdueTasks($userId);
    }
}
