<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Services\EventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EventController extends Controller
{
    public function __construct(
        protected EventService $eventService
    ) {}

    /**
     * GET /api/events
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $events = $this->eventService->getUserEvents($request->user()->id);

        return EventResource::collection($events);
    }

    /**
     * POST /api/events
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        $event = $this->eventService->createEvent(
            $request->user()->id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
            'data' => new EventResource($event),
        ], 201);
    }

    /**
     * GET /api/events/{id}
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $event = $this->eventService->getEvent($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found',
            ], 404);
        }

        // Check ownership
        if ($event->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new EventResource($event),
        ], 200);
    }

    /**
     * PUT/PATCH /api/events/{id}
     */
    public function update(UpdateEventRequest $request, int $id): JsonResponse
    {
        $event = $this->eventService->getEvent($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found',
            ], 404);
        }

        // Check ownership
        if ($event->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $this->eventService->updateEvent($id, $request->validated());
        $event = $this->eventService->getEvent($id);

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully',
            'data' => new EventResource($event),
        ], 200);
    }

    /**
     * DELETE /api/events/{id}
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $event = $this->eventService->getEvent($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found',
            ], 404);
        }

        // Check ownership
        if ($event->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $this->eventService->deleteEvent($id);

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully',
        ], 200);
    }
}
