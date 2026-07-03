@extends('layouts.app')

@section('title', 'Dashboard Quản trị - Đại học Phú Xuân')

@section('content')
    <!-- Dashboard Header -->
    <div class="p-5 mb-4 page-header-pxu shadow-sm position-relative">
        <div class="container-fluid py-4 position-relative" style="z-index: 1;">
            <span class="badge bg-warning text-dark mb-2 px-3 py-2 fw-bold text-uppercase" style="letter-spacing: 1px;">Admin Control Panel</span>
            <h1 class="display-5 fw-extrabold text-white">Dashboard Ban Quản Trị</h1>
            <p class="col-md-8 fs-5 text-white-50 mb-0">Xem số liệu thống kê tổng hợp hệ thống, kiểm soát phê duyệt và quản lý danh sách đăng ký tham gia sự kiện của sinh viên.</p>
        </div>
    </div>

    <!-- Statistics Metrics Grid -->
    <div class="row g-4 mb-5">
        <!-- Total Events -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-row align-items-center">
                <div class="rounded-circle bg-primary-subtle text-primary p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-calendar-days fs-3"></i>
                </div>
                <div>
                    <span class="text-muted small d-block fw-semibold text-uppercase">Sự kiện</span>
                    <h3 class="fw-bold text-dark mb-0">{{ $stats['total_events'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Total Registrations -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-row align-items-center">
                <div class="rounded-circle bg-warning-subtle text-warning p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-clipboard-list fs-3 text-warning"></i>
                </div>
                <div>
                    <span class="text-muted small d-block fw-semibold text-uppercase">Đăng ký</span>
                    <h3 class="fw-bold text-dark mb-0">{{ $stats['total_registrations'] }}</h3>
                    <small class="text-warning small fw-medium">{{ $stats['pending_registrations'] }} chờ duyệt</small>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-row align-items-center">
                <div class="rounded-circle bg-success-subtle text-success p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-users fs-3 text-success"></i>
                </div>
                <div>
                    <span class="text-muted small d-block fw-semibold text-uppercase">Tài khoản</span>
                    <h3 class="fw-bold text-dark mb-0">{{ $stats['total_users'] }}</h3>
                    <small class="text-muted small">SV: {{ $stats['students'] }} | BTC: {{ $stats['organizers'] }}</small>
                </div>
            </div>
        </div>

        <!-- Total Categories -->
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 d-flex flex-row align-items-center">
                <div class="rounded-circle bg-danger-subtle text-danger p-3 me-3 d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-tags fs-3 text-danger"></i>
                </div>
                <div>
                    <span class="text-muted small d-block fw-semibold text-uppercase">Danh mục</span>
                    <h3 class="fw-bold text-dark mb-0">{{ $stats['total_categories'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Registrations Management Section -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-5 bg-white">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center border-bottom pb-3 mb-4 gap-3">
            <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-square-check me-2 text-primary"></i>Phê duyệt đăng ký sự kiện</h4>
            <div class="d-flex gap-2">
                <!-- Export Buttons -->
                <a href="{{ route('admin.export.csv') }}" class="btn btn-success fw-semibold py-2">
                    <i class="fa-solid fa-file-csv me-1"></i> Xuất tất cả CSV
                </a>
            </div>
        </div>

        <!-- Filters Form -->
        <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-3 mb-4 border-bottom pb-4">
            <div class="col-md-5">
                <label for="search" class="form-label fw-bold text-muted small">Tìm kiếm thông tin</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-muted border-end-0"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control bg-light border-start-0" placeholder="Tên sinh viên, email, tên sự kiện...">
                </div>
            </div>

            <div class="col-md-3">
                <label for="status" class="form-label fw-bold text-muted small">Trạng thái đăng ký</label>
                <select name="status" id="status" class="form-select bg-light">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ phê duyệt (Pending)</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Đã phê duyệt (Confirmed)</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy/từ chối (Cancelled)</option>
                </select>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <div class="d-flex gap-2 w-100">
                    <button type="submit" class="btn btn-pxu-primary flex-grow-1 py-2">
                        <i class="fa-solid fa-filter me-1"></i> Tìm kiếm
                    </button>
                    @if(request()->has('search') || request()->has('status'))
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary py-2">
                            Xóa lọc
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Registrations Table -->
        @if($registrations->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fa-solid fa-inbox display-3 mb-3 opacity-30"></i>
                <h5>Không tìm thấy dữ liệu đăng ký nào!</h5>
                <p>Thử điều chỉnh lại bộ lọc hoặc tìm kiếm từ khóa khác.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light text-secondary">
                        <tr>
                            <th>Sinh viên</th>
                            <th>Thông tin liên hệ</th>
                            <th>Sự kiện đăng ký</th>
                            <th>Ghi chú sinh viên</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Thao tác duyệt</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registrations as $reg)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $reg->user->name }}</div>
                                    <div class="text-muted small">Đăng ký: {{ $reg->created_at->format('d/m/Y H:i') }}</div>
                                </td>
                                <td>
                                    <div><i class="fa-regular fa-envelope me-1 text-muted"></i>{{ $reg->user->email }}</div>
                                    <div class="text-muted small"><i class="fa-solid fa-phone me-1 text-muted"></i>{{ $reg->user->phone ?? 'Chưa cập nhật' }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">
                                        <a href="{{ route('events.show', $reg->event) }}" class="text-decoration-none text-dark hover-pxu-secondary">
                                            {{ $reg->event->title }}
                                        </a>
                                    </div>
                                    <span class="badge bg-light text-secondary border mt-1">{{ $reg->event->category->name }}</span>
                                </td>
                                <td>
                                    <span class="text-muted small" title="{{ $reg->note }}">{{ Str::limit($reg->note, 45) ?: 'Không có' }}</span>
                                </td>
                                <td>
                                    @if($reg->status === 'confirmed')
                                        <span class="badge bg-success"><i class="fa-solid fa-circle-check me-1"></i>Đồng ý</span>
                                    @elseif($reg->status === 'pending')
                                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-hourglass-half me-1"></i>Chờ duyệt</span>
                                    @else
                                        <span class="badge bg-danger"><i class="fa-solid fa-circle-xmark me-1"></i>Đã hủy</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        @if($reg->status !== 'cancelled')
                                            <form action="{{ route('registrations.approve', $reg) }}" method="POST">
                                                @csrf
                                                @if($reg->status === 'pending')
                                                    <button type="submit" name="status" value="confirmed" class="btn btn-sm btn-success py-1 px-2.5 fw-semibold" title="Phê duyệt" onclick="return confirm('Bạn có chắc muốn PHÊ DUYỆT đăng ký này?');">
                                                        <i class="fa-solid fa-check"></i> Duyệt
                                                    </button>
                                                    <button type="submit" name="status" value="cancelled" class="btn btn-sm btn-outline-danger py-1 px-2" title="Từ chối/Hủy" onclick="return confirm('Bạn có chắc muốn TỪ CHỐI đăng ký này?');">
                                                        Từ chối
                                                    </button>
                                                @elseif($reg->status === 'confirmed')
                                                    <button type="submit" name="status" value="cancelled" class="btn btn-sm btn-outline-danger py-1 px-2" title="Hủy bỏ trạng thái đồng ý" onclick="return confirm('Bạn có chắc muốn HỦY DUYỆT đăng ký này?');">
                                                        Hủy duyệt
                                                    </button>
                                                @endif
                                            </form>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Table Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $registrations->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
@endsection
