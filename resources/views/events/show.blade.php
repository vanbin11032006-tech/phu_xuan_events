@extends('layouts.app')

@section('title', $event->title . ' - Đại học Phú Xuân')

@section('content')
    <!-- Back button and Navigation context -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <a href="{{ route('events.index') }}" class="btn btn-light border px-3 py-2 fw-semibold text-muted">
            <i class="fa-solid fa-arrow-left me-2"></i> Quay lại danh sách
        </a>
        
        <!-- Manage Actions for Admins/Owners -->
        @auth
            @can('update', $event)
                <div class="d-flex gap-2">
                    <a href="{{ route('events.edit', $event) }}" class="btn btn-outline-primary py-2 px-3 fw-semibold">
                        <i class="fa-solid fa-edit me-1"></i> Chỉnh sửa sự kiện
                    </a>
                    
                    @can('delete', $event)
                        <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sự kiện này? Thao tác này không thể hoàn tác.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger py-2 px-3 fw-semibold">
                                <i class="fa-solid fa-trash-can me-1"></i> Xóa sự kiện
                            </button>
                        </form>
                    @else
                        <button class="btn btn-outline-secondary py-2 px-3 fw-semibold disabled" title="Không thể xóa khi đã có sinh viên đăng ký">
                            <i class="fa-solid fa-trash-can me-1"></i> Xóa (Đã có đăng ký)
                        </button>
                    @endcan
                </div>
            @endcan
        @endauth
    </div>

    <!-- Event Detail Layout -->
    <div class="row g-4 mb-5">
        <!-- Left Main Column (Details, Description, Organizer info, Registrations table) -->
        <div class="col-lg-8">
            <!-- Event Header and Banner -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="position-relative" style="height: 380px; overflow: hidden; background-color: var(--pxu-primary);">
                    @if($event->image)
                        <img src="{{ asset('storage/' . $event->image) }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $event->title }}">
                    @else
                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-white" style="background: linear-gradient(135deg, var(--pxu-primary) 0%, var(--pxu-primary-light) 100%);">
                            <i class="fa-solid fa-graduation-cap display-1 mb-3 text-warning opacity-75"></i>
                            <span class="fs-4 fw-bold text-uppercase tracking-wider">Trường Đại Học Phú Xuân</span>
                        </div>
                    @endif

                    <div class="position-absolute bottom-0 start-0 end-0 p-4" style="background: linear-gradient(to top, rgba(0,0,0,0.85), transparent);">
                        <span class="badge bg-pxu-secondary text-white py-2 px-3 mb-2 fw-semibold" style="background-color: var(--pxu-secondary);">
                            {{ $event->category->name }}
                        </span>
                        <h2 class="text-white fw-bold display-6 mb-0">{{ $event->title }}</h2>
                    </div>
                </div>

                <div class="card-body p-4 bg-white">
                    <!-- Event Tags -->
                    @if($event->tags->isNotEmpty())
                        <div class="mb-4 d-flex flex-wrap gap-2">
                            @foreach($event->tags as $tag)
                                <span class="badge bg-light text-secondary border px-3 py-2 fw-medium" style="border-radius: 20px;">
                                    <i class="fa-solid fa-tag me-1 text-muted"></i>{{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Event Description -->
                    <h4 class="fw-bold text-dark border-bottom pb-2 mb-3">Mô tả chi tiết</h4>
                    <div class="text-muted leading-relaxed mb-4" style="white-space: pre-line; line-height: 1.6;">
                        {!! nl2br(e($event->description)) !!}
                    </div>

                    <!-- Organizer Info -->
                    <div class="d-flex align-items-center mt-5 p-3 rounded-4 bg-light border border-light shadow-2xs">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 54px; height: 54px; font-size: 1.5rem;">
                            <i class="fa-solid fa-chalkboard-user"></i>
                        </div>
                        <div>
                            <span class="text-muted small d-block">Đơn vị tổ chức</span>
                            <strong class="text-dark fs-5">{{ $event->organizer->name }}</strong>
                            <span class="text-muted small ms-2">({{ $event->organizer->email }})</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- List of Registrations (Visible to Event Owner and Admin only) -->
            @auth
                @if(auth()->user()->isAdmin() || auth()->id() === $event->user_id)
                    <div class="card border-0 shadow-sm rounded-4 p-4 mt-4 bg-white">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                            <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-list-check me-2 text-primary"></i>Danh sách đăng ký</h4>
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.export.csv', ['event_id' => $event->id]) }}" class="btn btn-success btn-sm fw-semibold">
                                    <i class="fa-solid fa-file-csv me-1"></i> Xuất CSV
                                </a>
                            @endif
                        </div>

                        @if($event->registrations->isEmpty())
                            <div class="text-center py-4 text-muted">
                                <i class="fa-solid fa-folder-open display-6 mb-2 opacity-50"></i>
                                <p class="mb-0">Chưa có sinh viên nào đăng ký tham gia sự kiện này.</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light text-secondary">
                                        <tr>
                                            <th>Sinh viên</th>
                                            <th>Thông tin liên hệ</th>
                                            <th>Ghi chú</th>
                                            <th>Trạng thái</th>
                                            @if(auth()->user()->isAdmin())
                                                <th class="text-end">Duyệt</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($event->registrations as $reg)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $reg->user->name }}</div>
                                                    <div class="text-muted small">Đăng ký: {{ $reg->created_at->format('d/m/Y H:i') }}</div>
                                                </td>
                                                <td>
                                                    <div><i class="fa-regular fa-envelope me-1 text-muted"></i>{{ $reg->user->email }}</div>
                                                    <div class="text-muted"><i class="fa-solid fa-phone me-1 text-muted"></i>{{ $reg->user->phone ?? 'N/A' }}</div>
                                                </td>
                                                <td>
                                                    <span class="text-muted small" title="{{ $reg->note }}">{{ Str::limit($reg->note, 40) ?: 'N/A' }}</span>
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
                                                @if(auth()->user()->isAdmin())
                                                    <td class="text-end">
                                                        @if($reg->status === 'pending')
                                                            <div class="d-flex gap-1 justify-content-end">
                                                                <form action="{{ route('registrations.approve', $reg) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="confirmed">
                                                                    <button type="submit" class="btn btn-sm btn-success" title="Phê duyệt">
                                                                        <i class="fa-solid fa-check"></i>
                                                                    </button>
                                                                </form>
                                                                <form action="{{ route('registrations.approve', $reg) }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="cancelled">
                                                                    <button type="submit" class="btn btn-sm btn-danger" title="Từ chối/Hủy">
                                                                        <i class="fa-solid fa-times"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @elseif($reg->status === 'confirmed')
                                                            <form action="{{ route('registrations.approve', $reg) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="status" value="cancelled">
                                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hủy đăng ký">
                                                                    Hủy duyệt
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="text-muted small">-</span>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endif
            @endauth
        </div>

        <!-- Right Side Column (Time, Location, Status, Student action card) -->
        <div class="col-lg-4">
            <!-- Details Metadata Box -->
            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-white">
                <h4 class="fw-bold text-dark border-bottom pb-2 mb-3">Thông tin chi tiết</h4>
                
                <div class="mb-3 d-flex align-items-start">
                    <i class="fa-regular fa-clock text-primary fs-4 me-3 mt-1"></i>
                    <div>
                        <strong class="text-secondary small d-block">Thời gian bắt đầu</strong>
                        <span class="text-dark fw-medium">{{ $event->start_time->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <div class="mb-3 d-flex align-items-start">
                    <i class="fa-solid fa-clock text-primary fs-4 me-3 mt-1"></i>
                    <div>
                        <strong class="text-secondary small d-block">Thời gian kết thúc</strong>
                        <span class="text-dark fw-medium">{{ $event->end_time->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <div class="mb-3 d-flex align-items-start">
                    <i class="fa-solid fa-map-location-dot text-primary fs-4 me-3 mt-1"></i>
                    <div>
                        <strong class="text-secondary small d-block">Địa điểm tổ chức</strong>
                        <span class="text-dark fw-medium">{{ $event->location }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-start">
                    <i class="fa-solid fa-user-group text-primary fs-4 me-3 mt-1"></i>
                    <div>
                        <strong class="text-secondary small d-block">Sức chứa tối đa</strong>
                        <span class="text-dark fw-medium">{{ $event->capacity }} sinh viên</span>
                    </div>
                </div>
            </div>

            <!-- Student Registration Actions Module -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
                <div class="p-3 text-center fw-bold text-white bg-pxu-primary" style="background-color: var(--pxu-primary);">
                    <i class="fa-solid fa-id-card-clip me-2"></i>ĐĂNG KÝ THAM GIA
                </div>
                <div class="p-4">
                    @guest
                        <div class="text-center py-2 text-muted">
                            <i class="fa-solid fa-user-lock display-6 mb-3 opacity-50 text-secondary"></i>
                            <p class="mb-3">Vui lòng đăng nhập bằng tài khoản Sinh viên để đăng ký tham gia sự kiện.</p>
                            <a href="{{ route('login') }}" class="btn btn-pxu-primary w-100 py-2">
                                <i class="fa-solid fa-key me-1"></i> Đăng nhập ngay
                            </a>
                        </div>
                    @else
                        @if(auth()->user()->isStudent())
                            @if($isRegistered)
                                <!-- User is already registered -->
                                <div class="text-center py-2">
                                    @if($isRegistered->status === 'confirmed')
                                        <div class="alert alert-success border-0 shadow-2xs py-3 rounded-3 mb-3 text-center">
                                            <i class="fa-solid fa-circle-check fs-2 text-success mb-2 d-block"></i>
                                            <h5 class="alert-heading fw-bold">ĐÃ PHÊ DUYỆT</h5>
                                            <p class="small mb-0 text-muted">Ban tổ chức đã xác nhận sự tham gia của bạn.</p>
                                        </div>
                                    @elseif($isRegistered->status === 'pending')
                                        <div class="alert alert-warning border-0 shadow-2xs py-3 rounded-3 mb-3 text-center">
                                            <i class="fa-solid fa-clock-rotate-left fs-2 text-warning mb-2 d-block"></i>
                                            <h5 class="alert-heading fw-bold">CHỜ PHÊ DUYỆT</h5>
                                            <p class="small mb-0 text-muted">Đăng ký của bạn đang được ban tổ chức xem xét.</p>
                                        </div>
                                    @else
                                        <div class="alert alert-danger border-0 shadow-2xs py-3 rounded-3 mb-3 text-center">
                                            <i class="fa-solid fa-circle-xmark fs-2 text-danger mb-2 d-block"></i>
                                            <h5 class="alert-heading fw-bold">ĐÃ HỦY</h5>
                                            <p class="small mb-0 text-muted">Đăng ký của bạn đã bị hủy.</p>
                                        </div>
                                    @endif

                                    <!-- Student cancellation check (24 hour rules) -->
                                    @if($isRegistered->status !== 'cancelled')
                                        @php
                                            $canCancel = now()->diffInHours($event->start_time, false) >= 24;
                                        @endphp
                                        
                                        @if($canCancel)
                                            <form action="{{ route('registrations.destroy', $isRegistered) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đăng ký tham gia sự kiện này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger w-100 py-2.5 fw-semibold mt-2">
                                                    <i class="fa-solid fa-circle-minus me-1"></i> Hủy đăng ký tham gia
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-outline-secondary w-100 py-2.5 fw-semibold mt-2 disabled" title="Không thể hủy đăng ký trước sự kiện ít hơn 24 giờ.">
                                                <i class="fa-solid fa-circle-minus me-1"></i> Không thể hủy (Dưới 24h)
                                            </button>
                                            <small class="text-danger d-block mt-2">Chỉ có thể hủy trước 24 giờ khi sự kiện diễn ra.</small>
                                        @endif
                                    @else
                                        <!-- Re-register form if cancelled -->
                                        <form action="{{ route('registrations.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                                            <div class="mb-3">
                                                <label for="note" class="form-label small fw-bold">Ghi chú (Tùy chọn)</label>
                                                <textarea name="note" id="note" class="form-control" rows="2" placeholder="Ghi chú cho ban tổ chức..."></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-pxu-secondary w-100 py-2.5 fw-semibold">
                                                <i class="fa-solid fa-rotate-left me-1"></i> Đăng ký lại
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @else
                                <!-- User has not registered yet -->
                                @if($event->is_full)
                                    <div class="alert alert-secondary border-0 text-center py-3">
                                        <i class="fa-solid fa-triangle-exclamation fs-3 text-secondary mb-2 d-block"></i>
                                        <h6 class="fw-bold mb-1">SỰ KIỆN ĐÃ ĐẦY CHỖ</h6>
                                        <span class="small text-muted">Sức chứa tối đa ({{ $event->capacity }}) đã đạt giới hạn. Hẹn gặp bạn ở sự kiện khác!</span>
                                    </div>
                                @elseif($event->start_time->isPast())
                                    <div class="alert alert-danger border-0 text-center py-3">
                                        <i class="fa-solid fa-calendar-xmark fs-3 text-danger mb-2 d-block"></i>
                                        <h6 class="fw-bold mb-1">ĐÃ DIỄN RA</h6>
                                        <span class="small text-muted">Sự kiện đã kết thúc hoặc đang diễn ra, không chấp nhận đăng ký mới.</span>
                                    </div>
                                @else
                                    <!-- Fresh registration form -->
                                    <form action="{{ route('registrations.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                                        
                                        <div class="mb-3">
                                            <label for="note" class="form-label small fw-bold text-muted">Ghi chú gửi Ban tổ chức</label>
                                            <textarea name="note" id="note" class="form-control bg-light" rows="3" placeholder="Nhập lý do tham gia hoặc yêu cầu hỗ trợ đặc biệt..."></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-pxu-secondary w-100 py-2.5 fw-semibold">
                                            <i class="fa-solid fa-paper-plane me-1"></i> Gửi Yêu Cầu Tham Gia
                                        </button>
                                    </form>
                                @endif
                            @endif
                        @else
                            <!-- Logged in, but is Organizer or Admin -->
                            <div class="text-center py-3 text-muted">
                                <i class="fa-solid fa-user-tie fs-3 mb-2 text-primary d-block"></i>
                                <span class="small">Tài khoản quản lý ({{ auth()->user()->role }}) không thể đăng ký sự kiện.</span>
                            </div>
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </div>
@endsection
