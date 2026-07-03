@extends('layouts.app')

@section('title', 'Sự kiện của tôi - Đại học Phú Xuân')

@section('content')
    <div class="page-header-pxu p-5 shadow-sm">
        <div class="container-fluid py-4" style="z-index: 1;">
            <span class="badge bg-warning text-dark mb-2 px-3 py-2 fw-bold text-uppercase">Cá nhân</span>
            <h1 class="display-5 fw-extrabold text-white">Sự Kiện Của Tôi</h1>
            <p class="col-md-8 fs-5 text-white-50 mb-0">Theo dõi lịch trình, trạng thái đăng ký các sự kiện bạn đã tham gia hoặc đang chờ duyệt tại Trường Đại học Phú Xuân.</p>
        </div>
    </div>

    <!-- Nav tabs -->
    <ul class="nav nav-pills nav-fill bg-white p-2 rounded-4 shadow-sm border mb-4" id="registrationTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link py-3 fw-semibold active d-flex align-items-center justify-content-center gap-2" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="true" style="border-radius: 10px;">
                <i class="fa-solid fa-calendar-check fs-5"></i> Sắp diễn ra
                <span class="badge bg-primary rounded-pill">{{ $upcoming->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link py-3 fw-semibold d-flex align-items-center justify-content-center gap-2" id="past-tab" data-bs-toggle="tab" data-bs-target="#past" type="button" role="tab" aria-controls="past" aria-selected="false" style="border-radius: 10px;">
                <i class="fa-solid fa-clock-rotate-left fs-5"></i> Đã diễn ra
                <span class="badge bg-secondary rounded-pill">{{ $past->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link py-3 fw-semibold d-flex align-items-center justify-content-center gap-2" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab" aria-controls="cancelled" aria-selected="false" style="border-radius: 10px;">
                <i class="fa-solid fa-calendar-xmark fs-5"></i> Đã hủy
                <span class="badge bg-danger rounded-pill">{{ $cancelled->count() }}</span>
            </button>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content mb-5" id="registrationTabsContent">
        <!-- Upcoming Events Tab -->
        <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
            @if($upcoming->isEmpty())
                <div class="text-center py-5 rounded-4 shadow-sm bg-white border">
                    <i class="fa-regular fa-calendar-plus display-1 text-muted mb-4 opacity-50"></i>
                    <h5 class="fw-bold text-secondary">Bạn chưa đăng ký sự kiện sắp tới nào!</h5>
                    <p class="text-muted">Hãy tham khảo danh sách sự kiện và chọn cho mình một sự kiện phù hợp.</p>
                    <a href="{{ route('events.index') }}" class="btn btn-pxu-primary mt-2 py-2 px-4">
                        Khám phá sự kiện công khai
                    </a>
                </div>
            @else
                <div class="row g-4">
                    @foreach($upcoming as $reg)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card card-pxu h-100 border-0 overflow-hidden">
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <span class="badge bg-light text-secondary border py-1.5 px-2.5 small">
                                            {{ $reg->event->category->name }}
                                        </span>
                                        @if($reg->status === 'confirmed')
                                            <span class="badge bg-success py-1.5 px-2.5"><i class="fa-solid fa-circle-check me-1"></i>Đồng ý</span>
                                        @else
                                            <span class="badge bg-warning text-dark py-1.5 px-2.5"><i class="fa-solid fa-hourglass-half me-1"></i>Chờ duyệt</span>
                                        @endif
                                    </div>

                                    <h5 class="fw-bold text-dark mb-2">
                                        <a href="{{ route('events.show', $reg->event) }}" class="text-decoration-none text-dark hover-pxu-secondary">
                                            {{ $reg->event->title }}
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit($reg->event->description, 100) }}</p>
                                    
                                    <div class="border-top pt-3 text-muted small mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fa-regular fa-calendar-days text-primary me-2"></i>
                                            <strong>Bắt đầu:</strong>&nbsp;{{ $reg->event->start_time->format('d/m/Y H:i') }}
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-map-location-dot text-primary me-2"></i>
                                            <strong>Địa điểm:</strong>&nbsp;{{ $reg->event->location }}
                                        </div>
                                    </div>

                                    <!-- Cancellation Actions -->
                                    <div class="mt-auto">
                                        @php
                                            $canCancel = now()->diffInHours($reg->event->start_time, false) >= 24;
                                        @endphp
                                        
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('events.show', $reg->event) }}" class="btn btn-light btn-sm border flex-grow-1 py-2 fw-semibold">
                                                Chi tiết
                                            </a>
                                            @if($canCancel)
                                                <form action="{{ route('registrations.destroy', $reg) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đăng ký tham gia sự kiện này?');" class="flex-grow-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm w-100 py-2 fw-semibold">
                                                        Hủy đăng ký
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm btn-outline-secondary flex-grow-1 py-2 fw-semibold disabled" title="Không thể hủy trước sự kiện ít hơn 24 giờ.">
                                                    Khóa hủy
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Past Events Tab -->
        <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
            @if($past->isEmpty())
                <div class="text-center py-5 rounded-4 shadow-sm bg-white border">
                    <i class="fa-solid fa-hourglass-empty display-1 text-muted mb-4 opacity-50"></i>
                    <h5 class="fw-bold text-secondary">Chưa có lịch sử tham gia sự kiện nào!</h5>
                    <p class="text-muted">Các sự kiện bạn đã tham gia thành công sẽ xuất hiện tại đây.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($past as $reg)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.7); opacity: 0.85;">
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <span class="badge bg-light text-secondary border py-1.5 px-2.5 small">
                                            {{ $reg->event->category->name }}
                                        </span>
                                        <span class="badge bg-secondary py-1.5 px-2.5"><i class="fa-solid fa-calendar-minus me-1"></i>Đã diễn ra</span>
                                    </div>

                                    <h5 class="fw-bold text-secondary mb-2 text-decoration-line-through">
                                        {{ $reg->event->title }}
                                    </h5>
                                    
                                    <div class="border-top pt-3 text-muted small mt-auto">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fa-regular fa-calendar-check text-muted me-2"></i>
                                            <strong>Đã diễn ra ngày:</strong>&nbsp;{{ $reg->event->start_time->format('d/m/Y') }}
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-chalkboard-user text-muted me-2"></i>
                                            <strong>Đơn vị tổ chức:</strong>&nbsp;{{ $reg->event->organizer->name }}
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('events.show', $reg->event) }}" class="btn btn-light border btn-sm mt-3 w-100 py-2">
                                        Xem lại chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Cancelled Tab -->
        <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
            @if($cancelled->isEmpty())
                <div class="text-center py-5 rounded-4 shadow-sm bg-white border">
                    <i class="fa-regular fa-check-circle display-1 text-muted mb-4 opacity-50"></i>
                    <h5 class="fw-bold text-secondary">Không có đăng ký nào bị hủy!</h5>
                    <p class="text-muted">Tuyệt vời, bạn chưa từng hủy hoặc bị từ chối đăng ký sự kiện nào.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach($cancelled as $reg)
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="background: rgba(255, 255, 255, 0.6); opacity: 0.8;">
                                <div class="card-body p-4 d-flex flex-column">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <span class="badge bg-light text-secondary border py-1.5 px-2.5 small">
                                            {{ $reg->event->category->name }}
                                        </span>
                                        <span class="badge bg-danger py-1.5 px-2.5"><i class="fa-solid fa-ban me-1"></i>Đã hủy</span>
                                    </div>

                                    <h5 class="fw-bold text-muted mb-2">
                                        {{ $reg->event->title }}
                                    </h5>
                                    
                                    <div class="border-top pt-3 text-muted small mt-auto">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fa-regular fa-calendar-times text-danger me-2"></i>
                                            <strong>Sự kiện:</strong>&nbsp;{{ $reg->event->start_time->format('d/m/Y') }}
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-clock text-muted me-2"></i>
                                            <strong>Hủy lúc:</strong>&nbsp;{{ $reg->updated_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('events.show', $reg->event) }}" class="btn btn-outline-secondary btn-sm mt-3 w-100 py-2">
                                        Đăng ký lại sự kiện này
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
