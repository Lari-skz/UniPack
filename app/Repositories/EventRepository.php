<?php

namespace App\Repositories;

use App\Models\Event;
use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EventRepository implements EventRepositoryInterface
{
    public function __construct(
        protected Event $model
    ) {}

    public function getUserEvents(int $userId): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->orderBy('event_date')
            ->get();
    }

    public function find(int $id): ?Event
    {
        return $this->model->find($id);
    }

    public function create(array $data): Event
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

    public function getByDate(int $userId, string $date): Collection
    {
        return $this->model
            ->where('user_id', $userId)
            ->whereDate('event_date', $date)
            ->orderBy('event_time')
            ->get();
    }
}
