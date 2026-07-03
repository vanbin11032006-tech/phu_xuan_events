<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Models\Category;
use App\Models\Event;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Event::with(['category', 'tags', 'registrations'])
                      ->published()
                      ->upcoming();

        // 1. Filter by Category
        if ($request->has('category') && $request->category != '') {
            $query->byCategory($request->category);
        }

        // 2. Fulltext search on title/description/location
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // 3. Filter by Date (e.g. ?date=today, ?date=week, ?date=month)
        if ($request->has('date') && $request->date != '') {
            $date = $request->date;
            if ($date === 'today') {
                $query->whereDate('start_time', today());
            } elseif ($date === 'week') {
                $query->whereBetween('start_time', [now(), now()->addDays(7)]);
            } elseif ($date === 'month') {
                $query->whereBetween('start_time', [now(), now()->addMonth()]);
            }
        }

        // Sort by start time (soonest first)
        $events = $query->orderBy('start_time', 'asc')->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('events.index', compact('events', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Event::class);

        $categories = Category::all();
        $tags = Tag::all();

        return view('events.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
        $this->authorize('create', Event::class);

        $data = $request->validated();
        $data['user_id'] = auth()->id();

        // Handle Banner image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $event = Event::create($data);

        // Sync tags
        if ($request->has('tags')) {
            $event->tags()->sync($request->tags);
        }

        return redirect()->route('events.show', $event)
                         ->with('success', 'Sự kiện đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // Load relationships to avoid N+1 queries
        $event->load(['organizer', 'category', 'tags', 'registrations.user']);

        // Authorize view based on policy
        $this->authorize('view', $event);

        $isRegistered = null;
        if (auth()->check()) {
            $isRegistered = auth()->user()->registrations()
                                 ->where('event_id', $event->id)
                                 ->first();
        }

        return view('events.show', compact('event', 'isRegistered'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $this->authorize('update', $event);

        $categories = Category::all();
        $tags = Tag::all();

        return view('events.edit', compact('event', 'categories', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, Event $event)
    {
        $this->authorize('update', $event);

        $data = $request->validated();

        // Handle Banner image upload (delete old one if exists)
        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $event->update($data);

        // Sync tags
        $event->tags()->sync($request->input('tags', []));

        return redirect()->route('events.show', $event)
                         ->with('success', 'Cập nhật sự kiện thành công.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        // Soft delete event
        $event->delete();

        return redirect()->route('events.index')
                         ->with('success', 'Sự kiện đã được xóa thành công.');
    }
}
