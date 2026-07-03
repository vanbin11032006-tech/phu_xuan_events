<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created registration in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'note' => 'nullable|string|max:500',
        ]);

        $event = Event::findOrFail($request->event_id);
        $user = auth()->user();

        // 1. Only students can register (Admins & Organizers cannot)
        if (!$user->isStudent()) {
            return redirect()->back()->with('error', 'Chỉ tài khoản Sinh viên mới được đăng ký tham gia sự kiện.');
        }

        // 2. Check for duplicate registrations
        $existing = Registration::where('user_id', $user->id)
                                ->where('event_id', $event->id)
                                ->first();

        if ($existing) {
            if ($existing->status === 'cancelled') {
                // If previously cancelled, allow reactivating it to pending
                $existing->update([
                    'status' => 'pending',
                    'note' => $request->note,
                ]);
                return redirect()->route('events.show', $event)
                                 ->with('success', 'Đã đăng ký lại sự kiện thành công. Đang chờ ban tổ chức phê duyệt.');
            }
            return redirect()->back()->with('error', 'Bạn đã đăng ký tham gia sự kiện này rồi.');
        }

        // 3. Check event status is published
        if ($event->status !== 'published') {
            return redirect()->back()->with('error', 'Sự kiện chưa được công bố hoặc đã bị hủy.');
        }

        // 4. Check if event is full
        if ($event->is_full) {
            return redirect()->back()->with('error', 'Sự kiện này đã đủ số lượng người đăng ký.');
        }

        // 5. Check if event start time is in the future
        if ($event->start_time->isPast()) {
            return redirect()->back()->with('error', 'Sự kiện này đã diễn ra, không thể đăng ký.');
        }

        // Create the registration
        Registration::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status' => 'pending',
            'note' => $request->note,
        ]);

        return redirect()->route('events.show', $event)
                         ->with('success', 'Đăng ký tham gia sự kiện thành công! Vui lòng chờ phê duyệt.');
    }

    /**
     * Cancel a registration (Update status to cancelled).
     */
    public function destroy(Registration $registration)
    {
        // 1. Authorize owner of the registration
        if (auth()->id() !== $registration->user_id) {
            return redirect()->back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }

        $event = $registration->event;

        // 2. Can only cancel if start_time is at least 24 hours away
        if (now()->diffInHours($event->start_time, false) < 24) {
            return redirect()->back()->with('error', 'Bạn chỉ có thể hủy đăng ký trước khi sự kiện bắt đầu ít nhất 24 giờ.');
        }

        // 3. Update status to cancelled
        $registration->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Đã hủy đăng ký tham gia sự kiện.');
    }

    /**
     * Display the student's registrations list.
     */
    public function myRegistrations()
    {
        $user = auth()->user();

        if (!$user->isStudent()) {
            return redirect()->route('events.index')->with('error', 'Chỉ sinh viên mới có danh sách đăng ký cá nhân.');
        }

        // Eager load event, category and organizer
        $registrations = Registration::with(['event.category', 'event.organizer'])
                                     ->where('user_id', $user->id)
                                     ->orderBy('created_at', 'desc')
                                     ->get();

        $upcoming = $registrations->filter(function ($reg) {
            return $reg->status !== 'cancelled' && $reg->event->start_time->isFuture();
        });

        $past = $registrations->filter(function ($reg) {
            return $reg->status !== 'cancelled' && $reg->event->start_time->isPast();
        });

        $cancelled = $registrations->filter(function ($reg) {
            return $reg->status === 'cancelled';
        });

        return view('registrations.my', compact('upcoming', 'past', 'cancelled'));
    }

    /**
     * Approve or reject a registration (Admin only).
     */
    public function approve(Request $request, Registration $registration)
    {
        // Only Admin can approve
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Chỉ Ban quản trị (Admin) mới có quyền duyệt đăng ký.');
        }

        $request->validate([
            'status' => 'required|in:confirmed,cancelled',
        ]);

        $registration->update([
            'status' => $request->status,
        ]);

        $statusMsg = $request->status === 'confirmed' ? 'đã được phê duyệt' : 'đã bị từ chối/hủy';
        return redirect()->back()->with('success', "Đăng ký của sinh viên {$registration->user->name} {$statusMsg}.");
    }
}
