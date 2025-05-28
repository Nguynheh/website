@extends('frontend.layouts.master')
@section('head_css')
<style>
    /* Main container */
    .login-container {
        padding: 50px 0 30px 0;
        min-height: calc(100vh );
        display: flex;
        align-items: center;
        background: #f8fafc;
    }

    /* Card styling */
    .login-card {
        width: 100%;
        max-width: 450px;
        margin: 0 auto;
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    /* Card header */
    .login-header {
        background: linear-gradient(135deg, #3f78e0 0%, #2a52be 100%);
        color: white;
        padding: 1.5rem;
        text-align: center;
    }

    .login-header h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    /* Card body */
    .login-body {
        padding: 1.75rem;
        background: white;
    }

    /* Form elements */
    .form-floating {
        position: relative;
        margin-bottom: 1.25rem;
    }

    .form-control {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        border: 1px solid #e2e8f0;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: #3f78e0;
        box-shadow: 0 0 0 3px rgba(63, 120, 224, 0.1);
    }

    .form-label {
        position: absolute;
        top: 0.75rem;
        left: 1rem;
        font-size: 0.8rem;
        color: #64748b;
        transition: all 0.2s;
        pointer-events: none;
    }

    .form-control:focus + .form-label,
    .form-control:not(:placeholder-shown) + .form-label {
        top: -0.8rem;
        left: 0.8rem;
        font-size: 0.7rem;

        color: #3f78e0;
    }

    /* Password toggle */
    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #94a3b8;
        font-size: 1rem;
    }

    /* Login button */
    .btn-login {
        background: #3f78e0;
        border: none;
        border-radius: 8px;
        padding: 0.75rem;
        font-weight: 500;
        letter-spacing: 0.5px;
        transition: all 0.3s;
    }

    .btn-login:hover {
        background: #2a52be;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(63, 120, 224, 0.25);
    }

    /* Links */
    .auth-links {
        font-size: 0.85rem;
        text-align: center;
    }

    .auth-links a {
        color: #3f78e0;
        text-decoration: none;
        font-weight: 500;
    }

    .auth-links a:hover {
        text-decoration: underline;
    }

    /* Divider */
    .divider {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
        color: #94a3b8;
        font-size: 0.8rem;
    }

    .divider::before,
    .divider::after {
        content: "";
        flex: 1;
        border-bottom: 1px solid #e2e8f0;
    }

    .divider-text {
        padding: 0 0.75rem;
    }

    /* Social buttons */
    .social-login {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .social-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        transition: all 0.3s;
    }

    .social-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .login-container {
            padding: 30px 15px;
            min-height: calc(100vh - 100px);
        }
        
        .login-body {
            padding: 1.25rem;
        }
    }
</style>
@endsection

@section('content')
    @include('frontend.layouts.breadcrumb')
    
    @if (!auth()->user())
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2>Đăng nhập tài khoản</h2>
                <p>Hãy điền thông tin email và mật khẩu</p>
            </div>
            
            <div class="login-body">
                <form method="POST" action="{{ route('front.login') }}">
                    @csrf
                    
                    <!-- Email Field -->
                    <div class="form-floating mb-4">
                        <input type="email" name="email" id="loginEmail" class="form-control" placeholder="Email" required>
                        <label for="loginEmail" class="form-label">Email</label>
                    </div>
                    
                    <!-- Password Field -->
                    <div class="form-floating mb-4 position-relative">
                        <input type="password" name="password" id="loginPassword" class="form-control" placeholder="Mật khẩu" required>
                        <label for="loginPassword" class="form-label">Mật khẩu</label>
                        <span class="password-toggle"><i class="uil uil-eye"></i></span>
                    </div>
                    
                    <input type="hidden" name="plink" value="{{ isset($plink) ? $plink : '' }}">
                    
                    <!-- Login Button -->
                    <button type="submit" class="btn btn-primary btn-login w-100 mb-3">
                        Đăng nhập
                    </button>
                    
                    <!-- Links -->
                    <div class="auth-links mb-3">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
                        @endif
                    </div>
                    
                    <div class="auth-links">
                        Bạn chưa có tài khoản? <a href="{{ route('front.register') }}">Đăng ký</a>
                    </div>
                    
                    

    
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('scripts')
<script>
    // Password toggle functionality
    document.querySelector('.password-toggle').addEventListener('click', function() {
        const passwordInput = document.getElementById('loginPassword');
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