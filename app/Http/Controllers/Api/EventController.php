<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * GET /api/v1/events
     * List published events with filtering and pagination.
     */
    public function index(Request $request)
    {
        // Use withCount for performance instead of loading the full registrations collection.
        $query = Event::with(['category', 'tags', 'organizer'])
                      ->withCount(['registrations' => function ($query) {
                          $query->where('status', '!=', 'cancelled');
                      }])->published();

        // Filter: ?category=<id>
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filter: ?search=<keyword>
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter: ?status=<draft|published|cancelled>
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter: ?from_date=2024-01-01
        if ($request->filled('from_date')) {
            $query->where('start_time', '>=', $request->from_date);
        }

        // Filter: ?to_date=2024-12-31
        if ($request->filled('to_date')) {
            $query->where('start_time', '<=', $request->to_date . ' 23:59:59');
        }

        $events = $query->orderBy('start_time', 'asc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => EventResource::collection($events->items()),
            'meta' => [
                'pagination' => [
                    'total' => $events->total(),
                    'count' => $events->count(),
                    'per_page' => $events->perPage(),
                    'current_page' => $events->currentPage(),
                    'total_pages' => $events->lastPage(),
                    'has_more_pages' => $events->hasMorePages(),
                ],
            ],
        ]);
    }

    /**
     * GET /api/v1/events/{id}
     * Return single event details.
     */
    public function show(Event $event)
    {
        // Guests can only see published events
        if ($event->status !== 'published') {
            $user = auth('sanctum')->user();
            if (!$user || (!$user->isAdmin() && $user->id !== $event->user_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sự kiện này chưa được công bố hoặc không tồn tại.',
                ], 404);
            }
        }

        $event->load(['category', 'tags', 'organizer', 'registrations']);

        return response()->json([
            'success' => true,
            'data' => new EventResource($event),
        ]);
    }
}
