<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\Interfaces\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EventService
{
    public function __construct(
        protected EventRepositoryInterface $eventRepository
    ) {}

    /**
     * Get all events for user
     */
    public function getUserEvents(int $userId): Collection
    {
        return $this->eventRepository->getUserEvents($userId);
    }

    /**
     * Get event by ID
     */
    public function getEvent(int $id): ?Event
    {
        return $this->eventRepository->find($id);
    }

    /**
     * Create event with business logic
     */
    public function createEvent(int $userId, array $data): Event
    {
        // Business logic: set user_id
        $data['user_id'] = $userId;

        // Business logic: trim title
        $data['title'] = trim($data['title']);

        // Business logic: default color to cyberpunk pink
        if (!isset($data['color'])) {
            $data['color'] = '#FF006E';
        }

        return $this->eventRepository->create($data);
    }

    /**
     * Update event
     */
    public function updateEvent(int $id, array $data): bool
    {
        $event = $this->eventRepository->find($id);

        if (!$event) {
            return false;
        }

        if (isset($data['title'])) {
            $data['title'] = trim($data['title']);
        }

        return $this->eventRepository->update($id, $data);
    }

    /**
     * Delete event
     */
    public function deleteEvent(int $id): bool
    {
        return $this->eventRepository->delete($id);
    }

    /**
     * Get events for specific date
     */
    public function getEventsByDate(int $userId, string $date): Collection
    {
        return $this->eventRepository->getByDate($userId, $date);
    }
}
