<x-guest-layout>
    <h4 class="text-center mb-4 fw-bold text-dark"><i class="fa-solid fa-user-plus me-2 text-primary"></i>Đăng Ký Tài Khoản</h4>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold"><i class="fa-regular fa-user me-1 text-secondary"></i>Họ và tên</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required autofocus placeholder="Nguyễn Văn A">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold"><i class="fa-regular fa-envelope me-1 text-secondary"></i>Địa chỉ Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required placeholder="email@pxu.edu.vn">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Role -->
        <div class="mb-3">
            <label for="role" class="form-label fw-semibold"><i class="fa-solid fa-shield-halved me-1 text-secondary"></i>Vai trò (Role)</label>
            <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
                <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Sinh viên (Student)</option>
                <option value="organizer" {{ old('role') === 'organizer' ? 'selected' : '' }}>Ban tổ chức (Organizer)</option>
            </select>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Phone -->
        <div class="mb-3">
            <label for="phone" class="form-label fw-semibold"><i class="fa-solid fa-phone me-1 text-secondary"></i>Số điện thoại</label>
            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="0987654321">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold"><i class="fa-solid fa-key me-1 text-secondary"></i>Mật khẩu</label>
            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Tối thiểu 8 ký tự">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold"><i class="fa-solid fa-check-double me-1 text-secondary"></i>Xác nhận mật khẩu</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required placeholder="Nhập lại mật khẩu">
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-pxu-secondary w-100 py-2.5 mb-3">
            <i class="fa-solid fa-user-check me-2"></i> Đăng Ký Tài Khoản
        </button>

        <div class="text-center">
            <span class="text-muted small">Đã có tài khoản?</span>
            <a href="{{ route('login') }}" class="small fw-bold ms-1">Đăng nhập</a>
        </div>
    </form>
</x-guest-layout>
