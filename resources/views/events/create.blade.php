@extends('layouts.app')

@section('title', 'Đăng sự kiện mới - Đại học Phú Xuân')

@section('content')
    <div class="row justify-content-center mb-5">
        <div class="col-lg-10">
            <!-- Header -->
            <div class="mb-4">
                <a href="{{ route('events.index') }}" class="btn btn-link link-secondary ps-0 text-decoration-none">
                    <i class="fa-solid fa-arrow-left me-1"></i> Quay lại sự kiện
                </a>
                <h2 class="fw-bold text-dark mt-2"><i class="fa-solid fa-circle-plus text-primary me-2"></i>Đăng sự kiện mới</h2>
                <p class="text-muted">Điền đầy đủ thông tin để công bố hoặc lưu nháp một sự kiện dành cho sinh viên PXU.</p>
            </div>

            <!-- Form Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Event Title -->
                    <div class="mb-4">
                        <label for="title" class="form-label fw-bold text-dark"><i class="fa-solid fa-heading me-2 text-secondary"></i>Tiêu đề sự kiện <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required placeholder="Ví dụ: Ngày hội việc làm Công nghệ thông tin 2026">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Tiêu đề phải từ 10 đến 200 ký tự.</small>
                    </div>

                    <div class="row g-4 mb-4">
                        <!-- Category -->
                        <div class="col-md-6">
                            <label for="category_id" class="form-label fw-bold text-dark"><i class="fa-solid fa-layer-group me-2 text-secondary"></i>Danh mục sự kiện <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Capacity -->
                        <div class="col-md-6">
                            <label for="capacity" class="form-label fw-bold text-dark"><i class="fa-solid fa-users-viewfinder me-2 text-secondary"></i>Số lượng người tham gia tối đa <span class="text-danger">*</span></label>
                            <input type="number" name="capacity" id="capacity" class="form-control @error('capacity') is-invalid @enderror" value="{{ old('capacity') }}" required placeholder="Ví dụ: 100" min="10" max="5000">
                            @error('capacity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Nhập số lượng từ 10 đến 5000.</small>
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <!-- Start Time -->
                        <div class="col-md-6">
                            <label for="start_time" class="form-label fw-bold text-dark"><i class="fa-regular fa-calendar-plus me-2 text-secondary"></i>Thời gian bắt đầu <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="start_time" id="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- End Time -->
                        <div class="col-md-6">
                            <label for="end_time" class="form-label fw-bold text-dark"><i class="fa-regular fa-calendar-minus me-2 text-secondary"></i>Thời gian kết thúc <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="end_time" id="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time') }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row g-4 mb-4">
                        <!-- Location -->
                        <div class="col-md-6">
                            <label for="location" class="form-label fw-bold text-dark"><i class="fa-solid fa-map-location-dot me-2 text-secondary"></i>Địa điểm tổ chức <span class="text-danger">*</span></label>
                            <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}" required placeholder="Ví dụ: Hội trường A - Tòa nhà hiệu bộ">
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-bold text-dark"><i class="fa-solid fa-toggle-on me-2 text-secondary"></i>Trạng thái <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Công khai (Published)</option>
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Lưu nháp (Draft)</option>
                                <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Hủy bỏ (Cancelled)</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Banner Image -->
                    <div class="mb-4">
                        <label for="image" class="form-label fw-bold text-dark"><i class="fa-regular fa-image me-2 text-secondary"></i>Ảnh banner sự kiện (Không bắt buộc)</label>
                        <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Chấp nhận ảnh dung lượng tối đa 2MB (jpg, png, webp, etc.)</small>
                    </div>

                    <!-- Tags -->
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark d-block"><i class="fa-solid fa-tags me-2 text-secondary"></i>Thẻ từ khóa (Tags)</label>
                        <div class="card bg-light p-3 border rounded-3" style="max-height: 180px; overflow-y: auto;">
                            <div class="row g-2">
                                @foreach($tags as $tag)
                                    <div class="col-6 col-sm-4 col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tags[]" value="{{ $tag->id }}" id="tag_{{ $tag->id }}" {{ is_array(old('tags')) && in_array($tag->id, old('tags')) ? 'checked' : '' }}>
                                            <label class="form-check-label text-truncate d-block" for="tag_{{ $tag->id }}" title="{{ $tag->name }}">
                                                {{ $tag->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @error('tags')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold text-dark"><i class="fa-solid fa-file-invoice me-2 text-secondary"></i>Mô tả chi tiết sự kiện <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="8" required placeholder="Nhập lịch trình, nội dung chính, đối tượng tham gia và các yêu cầu khác cho sự kiện...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Mô tả phải có độ dài ít nhất 50 ký tự.</small>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-2 border-top pt-4 mt-4">
                        <a href="{{ route('events.index') }}" class="btn btn-outline-secondary py-2.5 px-4 fw-semibold">
                            Hủy bỏ
                        </a>
                        <button type="submit" class="btn btn-pxu-secondary py-2.5 px-4 fw-semibold">
                            <i class="fa-solid fa-paper-plane me-1"></i> Tạo sự kiện
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
