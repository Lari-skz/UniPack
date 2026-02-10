<?php

namespace App\Repositories\Interfaces;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

interface EventRepositoryInterface
{
    /**
     * Get all events for a user
     */
    public function getUserEvents(int $userId): Collection;

    /**
     * Find event by ID
     */
    public function find(int $id): ?Event;

    /**
     * Create new event
     */
    public function create(array $data): Event;

    /**
     * Update event
     */
    public function update(int $id, array $data): bool;

    /**
     * Delete event
     */
    public function delete(int $id): bool;

    /**
     * Get events for a specific date
     */
    public function getByDate(int $userId, string $date): Collection;
}
