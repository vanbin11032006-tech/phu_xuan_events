@extends('layouts.app')

@section('title', 'Danh sách Sự kiện - Đại học Phú Xuân')

@section('content')
    <!-- Welcome Header / Hero Section -->
    <div class="p-5 mb-4 page-header-pxu shadow-sm position-relative">
        <div class="container-fluid py-4 position-relative" style="z-index: 1;">
            <span class="badge bg-warning text-dark mb-2 px-3 py-2 fw-bold text-uppercase" style="letter-spacing: 1px;">PXU Event Hub</span>
            <h1 class="display-5 fw-extrabold text-white">Hệ thống Quản lý Sự kiện Sinh viên</h1>
            <p class="col-md-8 fs-5 text-white-50 mb-0">Cập nhật nhanh nhất các cuộc thi, hội thảo, chuyên đề và hoạt động ngoại khóa tại Trường Đại học Phú Xuân. Đăng ký tham gia ngay để tích điểm rèn luyện!</p>
        </div>
    </div>

    <!-- Search and Filter Panel -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 p-4" style="background-color: #ffffff;">
        <form action="{{ route('events.index') }}" method="GET" class="row g-3">
            <!-- Search Query -->
            <div class="col-md-4">
                <label for="search" class="form-label fw-bold text-muted">Tìm kiếm sự kiện</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control bg-light border-start-0 ps-0" placeholder="Nhập tên sự kiện, từ khóa, địa điểm...">
                </div>
            </div>

            <!-- Category Filter -->
            <div class="col-md-3">
                <label for="category" class="form-label fw-bold text-muted">Danh mục sự kiện</label>
                <select name="category" id="category" class="form-select bg-light">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date Filter -->
            <div class="col-md-3">
                <label for="date" class="form-label fw-bold text-muted">Thời gian tổ chức</label>
                <select name="date" id="date" class="form-select bg-light">
                    <option value="">Mọi thời gian</option>
                    <option value="today" {{ request('date') === 'today' ? 'selected' : '' }}>Hôm nay</option>
                    <option value="week" {{ request('date') === 'week' ? 'selected' : '' }}>7 ngày tới</option>
                    <option value="month" {{ request('date') === 'month' ? 'selected' : '' }}>30 ngày tới</option>
                </select>
            </div>

            <!-- Filter Buttons -->
            <div class="col-md-2 d-flex align-items-end">
                <div class="d-flex gap-2 w-100">
                    <button type="submit" class="btn btn-pxu-primary flex-grow-1 py-2">
                        <i class="fa-solid fa-filter me-1"></i> Lọc
                    </button>
                    @if(request()->has('search') || request()->has('category') || request()->has('date'))
                        <a href="{{ route('events.index') }}" class="btn btn-outline-secondary py-2" title="Xóa bộ lọc">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Events Grid Section -->
    <div class="row mb-3">
        <div class="col-12">
            <h3 class="fw-bold text-dark mb-4 d-flex align-items-center">
                <i class="fa-solid fa-bolt text-warning me-2"></i> 
                Sự kiện sắp diễn ra
                <span class="badge bg-light text-muted border fs-6 ms-2 py-1.5 px-3 rounded-pill fw-semibold">{{ $events->total() }} sự kiện</span>
            </h3>
        </div>
    </div>

    @if($events->isEmpty())
        <div class="text-center py-5 rounded-4 shadow-sm bg-white mb-5 border">
            <i class="fa-regular fa-calendar-times display-1 text-muted mb-4 opacity-50"></i>
            <h4 class="fw-bold text-secondary">Không tìm thấy sự kiện nào!</h4>
            <p class="text-muted">Hãy thử thay đổi điều kiện tìm kiếm hoặc từ khóa khác.</p>
            <a href="{{ route('events.index') }}" class="btn btn-pxu-primary mt-2 py-2 px-4">
                <i class="fa-solid fa-arrows-spin me-2"></i> Hiển thị tất cả
            </a>
        </div>
    @else
        <!-- Grid list of events -->
        <div class="row g-4 mb-5">
            @foreach($events as $event)
                <div class="col-12 col-md-6 col-lg-4">
                    <x-event-card :event="$event" />
                </div>
            @endforeach
        </div>

        <!-- Custom Pagination Links -->
        <div class="d-flex justify-content-center mt-4 mb-5">
            {{ $events->links('pagination::bootstrap-5') }}
        </div>
    @endif
@endsection
