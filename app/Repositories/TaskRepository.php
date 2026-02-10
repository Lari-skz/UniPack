<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class TaskRepository implements TaskRepositoryInterface
{
    public function __construct(
        protected Task $model
    ) {}

    public function getUserTasks(int $userId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->with('category')
            ->get();
    }

    public function find(int $id): ?Task
    {
        return $this->model
            ->with('category', 'user')
            ->find($id);
    }

    public function create(array $data): Task
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $record = $this->model->find($id);

        if (!$record) {
            return false;
        }

        return $record->update($data);
    }

    public function delete(int $id): bool
    {
        $record = $this->model->find($id);

        if (!$record) {
            return false;
        }

        return $record->delete();
    }

    public function getByStatus(int $userId, string $status): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('status', $status)
            ->with('category')
            ->get();
    }

    public function getOverdueTasks(int $userId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('due_date', '<', Carbon::today())
            ->where('status', '!=', 'completed')
            ->with('category')
            ->get();
    }
}
