<x-guest-layout>
    <h4 class="text-center mb-4 fw-bold text-dark"><i class="fa-solid fa-lock me-2 text-primary"></i>Đăng Nhập Hệ Thống</h4>

    @if (session('status'))
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold"><i class="fa-regular fa-envelope me-1 text-secondary"></i>Địa chỉ Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus placeholder="name@example.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label fw-semibold"><i class="fa-solid fa-key me-1 text-secondary"></i>Mật khẩu</label>
            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Nhập mật khẩu">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                <label class="form-check-label text-muted small" for="remember_me">
                    Ghi nhớ đăng nhập
                </label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="small">Quên mật khẩu?</a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-pxu-secondary w-100 py-2.5 mb-3">
            <i class="fa-solid fa-right-to-bracket me-2"></i> Đăng Nhập
        </button>

        <div class="text-center">
            <span class="text-muted small">Chưa có tài khoản?</span>
            <a href="{{ route('register') }}" class="small fw-bold ms-1">Đăng ký ngay</a>
        </div>
    </form>
</x-guest-layout>
