@props(['event'])

<div class="card card-pxu h-100 overflow-hidden border-0">
    <!-- Banner Image / Fallback -->
    <div class="position-relative" style="height: 180px; overflow: hidden; background-color: var(--pxu-primary);">
        @if($event->image)
            <img src="{{ asset('storage/' . $event->image) }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $event->title }}">
        @else
            <!-- Generate a premium SVG placeholder with gradient -->
            <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-white" style="background: linear-gradient(135deg, var(--pxu-primary) 0%, var(--pxu-primary-light) 100%);">
                <i class="fa-solid fa-graduation-cap fs-1 mb-2 text-warning opacity-75"></i>
                <span class="small fw-semibold text-uppercase tracking-wider">Phú Xuân Events</span>
            </div>
        @endif
        
        <!-- Category Badge -->
        <span class="position-absolute top-3 start-3 badge bg-pxu-secondary text-white py-2 px-3 fw-semibold shadow-sm" style="background-color: var(--pxu-secondary); border-radius: 30px; font-size: 0.8rem; top: 12px; left: 12px;">
            {{ $event->category->name }}
        </span>

        <!-- Status Badge for Owner/Admin -->
        @auth
            @if(auth()->user()->isAdmin() || auth()->id() === $event->user_id)
                <span class="position-absolute top-3 end-3 badge py-2 px-2.5 fw-semibold shadow-sm text-capitalize {{ $event->status === 'published' ? 'bg-success' : ($event->status === 'draft' ? 'bg-secondary' : 'bg-danger') }}" style="border-radius: 6px; top: 12px; right: 12px; font-size: 0.75rem;">
                    {{ $event->status === 'published' ? 'Công khai' : ($event->status === 'draft' ? 'Nháp' : 'Hủy bỏ') }}
                </span>
            @endif
        @endauth
    </div>

    <!-- Card Body -->
    <div class="card-body d-flex flex-column p-4">
        <!-- Title -->
        <h5 class="card-title fw-bold text-dark mb-3 line-clamp-2" style="font-size: 1.15rem; min-height: 2.8rem; line-height: 1.4;">
            <a href="{{ route('events.show', $event) }}" class="text-decoration-none text-dark hover-pxu-secondary">
                {{ $event->title }}
            </a>
        </h5>

        <!-- Meta Info -->
        <div class="mb-3 text-muted small flex-grow-1">
            <div class="d-flex align-items-center mb-2">
                <i class="fa-regular fa-calendar-days text-secondary me-2 fs-6"></i>
                <span>{{ $event->start_time->format('d/m/Y H:i') }}</span>
            </div>
            <div class="d-flex align-items-center mb-2">
                <i class="fa-solid fa-map-location-dot text-secondary me-2 fs-6"></i>
                <span class="text-truncate" style="max-width: 250px;">{{ $event->location }}</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-users text-secondary me-2 fs-6"></i>
                @php
                    $registered = $event->registrations->where('status', '!=', 'cancelled')->count();
                    $remaining = max(0, $event->capacity - $registered);
                @endphp
                <span>
                    Đã đăng ký: <strong>{{ $registered }}</strong> / {{ $event->capacity }} (Còn {{ $remaining }} chỗ)
                </span>
            </div>
        </div>

        <!-- Tags Row -->
        @if($event->tags->isNotEmpty())
            <div class="mb-3 d-flex flex-wrap gap-1">
                @foreach($event->tags->take(3) as $tag)
                    <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 0.7rem; border-radius: 4px;">
                        #{{ $tag->name }}
                    </span>
                @endforeach
                @if($event->tags->count() > 3)
                    <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 0.7rem; border-radius: 4px;">
                        +{{ $event->tags->count() - 3 }}
                    </span>
                @endif
            </div>
        @endif

        <!-- Action / Button -->
        <div class="mt-auto pt-2 border-top">
            @if($event->is_full)
                <button class="btn btn-secondary w-100 disabled py-2 fw-semibold" style="border-radius: 8px;">
                    <i class="fa-solid fa-ban me-1"></i> Sự kiện đã đầy chỗ
                </button>
            @else
                <a href="{{ route('events.show', $event) }}" class="btn btn-pxu-outline w-100 py-2">
                    Xem chi tiết <i class="fa-solid fa-arrow-right-long ms-1"></i>
                </a>
            @endif
        </div>
    </div>
</div>
