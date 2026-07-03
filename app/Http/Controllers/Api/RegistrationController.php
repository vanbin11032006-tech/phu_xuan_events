<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RegistrationResource;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * GET /api/v1/user/registrations
     * M4.5 – Return all registrations of the authenticated user.
     */
    public function index(Request $request)
    {
        $registrations = Registration::with(['event.category', 'event.organizer', 'event.tags', 'event.registrations'])
                                     ->where('user_id', $request->user()->id)
                                     ->orderBy('created_at', 'desc')
                                     ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => RegistrationResource::collection($registrations->items()),
            'meta' => [
                'pagination' => [
                    'total' => $registrations->total(),
                    'current_page' => $registrations->currentPage(),
                    'total_pages' => $registrations->lastPage(),
                    'has_more_pages' => $registrations->hasMorePages(),
                ],
            ],
        ]);
    }

    /**
     * POST /api/v1/registrations
     * M4.4 – Register for an event (Sanctum token required, student only).
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|integer|exists:events,id',
            'note' => 'nullable|string|max:500',
        ]);

        $user = $request->user();
        $event = Event::with('registrations')->findOrFail($request->event_id);

        // 1. Role check – only students register
        if (!$user->isStudent()) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ tài khoản Sinh viên mới được đăng ký tham gia sự kiện.',
            ], 403);
        }

        // 2. Event must be published
        if ($event->status !== 'published') {
            return response()->json([
                'success' => false,
                'message' => 'Sự kiện chưa được công bố hoặc đã bị hủy.',
            ], 422);
        }

        // 3. Event must not have started yet
        if ($event->start_time->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Sự kiện này đã diễn ra, không thể đăng ký.',
            ], 422);
        }

        // 4. Capacity check
        if ($event->is_full) {
            return response()->json([
                'success' => false,
                'message' => 'Sự kiện này đã đủ số lượng người đăng ký.',
            ], 422);
        }

        // 5. Duplicate check
        $existing = Registration::where('user_id', $user->id)
                                ->where('event_id', $event->id)
                                ->first();

        if ($existing) {
            if ($existing->status === 'cancelled') {
                $existing->update(['status' => 'pending', 'note' => $request->note]);
                return response()->json([
                    'success' => true,
                    'message' => 'Đã đăng ký lại thành công. Vui lòng chờ phê duyệt.',
                    'data' => new RegistrationResource($existing->load('event')),
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã đăng ký tham gia sự kiện này rồi.',
            ], 422);
        }

        // 6. Create registration
        $registration = Registration::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'pending',
            'note' => $request->note,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đăng ký tham gia thành công! Vui lòng chờ phê duyệt.',
            'data' => new RegistrationResource($registration->load('event.category')),
        ], 201);
    }

    /**
     * DELETE /api/v1/registrations/{id}
     * Cancel a registration.
     */
    public function destroy(Request $request, Registration $registration)
    {
        // Owner check
        if ($request->user()->id !== $registration->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền hủy đăng ký này.',
            ], 403);
        }

        $event = $registration->event;

        // 24-hour rule
        if (now()->diffInHours($event->start_time, false) < 24) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ có thể hủy đăng ký trước khi sự kiện bắt đầu ít nhất 24 giờ.',
            ], 422);
        }

        $registration->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Đã hủy đăng ký tham gia sự kiện.',
        ]);
    }
}
