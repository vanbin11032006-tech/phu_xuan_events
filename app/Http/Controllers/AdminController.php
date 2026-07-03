<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the Admin Dashboard.
     */
    public function dashboard(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('events.index')->with('error', 'Bạn không có quyền truy cập trang quản trị.');
        }

        // 1. Calculate stats
        $stats = [
            'total_events' => Event::count(),
            'total_registrations' => Registration::count(),
            'pending_registrations' => Registration::where('status', 'pending')->count(),
            'confirmed_registrations' => Registration::where('status', 'confirmed')->count(),
            'total_users' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'organizers' => User::where('role', 'organizer')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'total_categories' => Category::count(),
        ];

        // 2. Fetch registrations with filters
        $registrationsQuery = Registration::with(['user', 'event.category'])->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status != '') {
            $registrationsQuery->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $registrationsQuery->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('event', function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        $registrations = $registrationsQuery->paginate(15)->withQueryString();
        $events = Event::orderBy('title')->get();

        return view('admin.dashboard', compact('stats', 'registrations', 'events'));
    }

    /**
     * Export registrations list to CSV.
     */
    public function exportCsv(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('events.index')->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }

        $eventId = $request->query('event_id');
        $query = Registration::with(['user', 'event']);

        if ($eventId) {
            $query->where('event_id', $eventId);
            $event = Event::findOrFail($eventId);
            $filename = "danh_sach_dang_ky_su_kien_" . $event->id . "_" . date('Ymd_His') . ".csv";
        } else {
            $filename = "danh_sach_tat_ca_dang_ky_" . date('Ymd_His') . ".csv";
        }

        $registrations = $query->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($registrations) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM to support Vietnamese characters in Microsoft Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV Headers
            fputcsv($file, [
                'ID Đăng ký', 
                'Họ và tên', 
                'Email', 
                'Số điện thoại', 
                'Sự kiện', 
                'Thời gian đăng ký', 
                'Trạng thái', 
                'Ghi chú của sinh viên'
            ]);

            // CSV Content
            foreach ($registrations as $reg) {
                fputcsv($file, [
                    $reg->id,
                    $reg->user->name,
                    $reg->user->email,
                    $reg->user->phone ?? 'Chưa cập nhật',
                    $reg->event->title,
                    $reg->created_at->format('d/m/Y H:i'),
                    $reg->status,
                    $reg->note ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
