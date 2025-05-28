@extends('frontend.layouts.master')
@section('head_css')
<style>
    .auth-wrapper {
        min-height: calc(130vh);
        display: flex;
        align-items: center;
        padding: 20px 0;
        background: #f8f9fa;
    }
    .auth-card {
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        border: none;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }
    .auth-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.25rem;
        text-align: center;
    }
    .auth-header h2 {
        font-size: 1.4rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    .auth-header p {
        font-size: 0.85rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    .auth-body {
        padding: 1.5rem;
        background: white;
    }
    .form-label {
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 0.4rem;
        color: #495057;
    }
    .form-control {
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
        height: calc(1.5em + 0.75rem + 2px);
    }
    .input-group-text {
        padding: 0 0.75rem;
        font-size: 0.9rem;
    }
    .btn-auth {
        padding: 0.5rem;
        font-size: 0.9rem;
        font-weight: 500;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        margin-top: 0.5rem;
    }
    .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #6c757d;
        font-size: 0.9rem;
    }
    .divider {
        font-size: 0.8rem;
        color: #6c757d;
        margin: 1rem 0;
    }
    .login-link {
        font-size: 0.85rem;
    }

    @media (max-width: 576px) {
        .auth-wrapper {
            padding: 15px 0;
            min-height: calc(100vh - 100px);
        }
        .auth-body {
            padding: 1.25rem;
        }
    }
</style>
@endsection

@section('content')
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Đăng ký tài khoản</h2>
            <p>Hoàn thành trong 1 phút</p>
        </div>
        <div class="auth-body">
            <form method="POST" action="{{route('front.register')}}">
                @csrf
                
                @if ($errors->has('g-recaptcha-response'))
                    <div class="alert alert-danger mb-3 py-2 px-3" style="font-size: 0.8rem;">
                        {{ $errors->first('g-recaptcha-response') }}
                    </div>
                @endif
                
                <!-- Họ và tên -->
                <div class="mb-3">
                    <label for="full_name" class="form-label">Họ và tên</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Nguyễn Văn A" required>
                        <span class="input-group-text"><i class="uil uil-user"></i></span>
                    </div>
                </div>
                
                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <input type="email" class="form-control" id="email" name="email" placeholder="example@email.com" required>
                        <span class="input-group-text"><i class="uil uil-envelope"></i></span>
                    </div>
                </div>
                
                <!-- Mật khẩu -->
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Ít nhất 6 ký tự" required>
                </div>
                
                <!-- Giới thiệu bản thân -->
                <div class="mb-3">
                    <label for="description" class="form-label">Giới thiệu bản thân</label>
                    <textarea class="form-control" id="description" name="description" rows="2" placeholder="Mô tả ngắn về bạn"></textarea>
                </div>
                
                <!-- Điện thoại và Địa chỉ -->
                <div class="row g-2">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Điện thoại</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="0987 654 321">
                            <span class="input-group-text"><i class="uil uil-phone"></i></span>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="address" name="address" placeholder="Số nhà, đường, quận">
                            <span class="input-group-text"><i class="uil uil-map-marker"></i></span>
                        </div>
                    </div>
                </div>
                
                <!-- Câu hỏi bảo mật -->
                <div class="mb-3">
                    <label for="ketqua" class="form-label">1 + 1 = ? (viết bằng chữ thường)</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="ketqua" name="ketqua" placeholder="hai" required>
                        <span class="input-group-text"><i class="uil uil-shield-check"></i></span>
                    </div>
                </div>
                
                <!-- Điều khoản -->
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="terms" required>
                    <label class="form-check-label" for="terms" style="font-size: 0.8rem;">
                        Tôi đồng ý với <a href="#" style="text-decoration: underline;">điều khoản</a> và <a href="#" style="text-decoration: underline;">chính sách</a>
                    </label>
                </div>
                
                <!-- Nút đăng ký -->
                <button type="submit" class="btn btn-primary btn-auth w-100">
                    <i class="uil uil-user-plus me-1"></i> Đăng ký ngay
                </button>
                
                <!-- Đăng nhập -->
                <div class="text-center mt-3">
                    <p class="login-link mb-0">Đã có tài khoản? <a href="{{route('front.login')}}" style="font-weight: 500;">Đăng nhập</a></p>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle password visibility
    document.querySelector('.password-toggle').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('uil-eye');
            icon.classList.add('uil-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('uil-eye-slash');
            icon.classList.add('uil-eye');
        }
    });
</script>
@endsection