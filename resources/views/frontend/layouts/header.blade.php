<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<header class="relative wrapper bg-soft-primary !bg-[#edf2fc]">
    <nav class="navbar navbar-expand-lg center-nav navbar-light navbar-bg-light">
        <div class="container xl:flex-row lg:flex-row !flex-nowrap items-center">
            <div class="navbar-brand w-full">
                <a href="{{ route('home') }}">
                <img src="{{ asset('storage/avatar/logo.png') }}" alt="Logo LMS"
                    class="h-12 md:h-16 w-auto object-contain transition duration-300 hover:opacity-80">

                </a>
            </div>
            <div class="navbar-collapse offcanvas offcanvas-nav offcanvas-start">
                <div class="offcanvas-header xl:hidden lg:hidden flex items-center justify-between flex-row p-6">
                    <h3 class="text-white xl:text-[1.5rem] !text-[calc(1.275rem_+_0.3vw)] !mb-0">
                        {{ $setting->short_name }}</h3>
                        <button type="button"
                            class="text-white bg-inherit transition-all duration-200 ease-in-out hover:bg-[rgba(0,0,0,0.11)] focus:outline-none px-3 py-1 rounded"
                            data-bs-dismiss="offcanvas" aria-label="Close">
                            Đóng
                        </button>
                </div>
                <div class="offcanvas-body xl:!ml-auto lg:!ml-auto flex flex-col !h-full">
                @auth
                    @if(Auth::user()->role == 'student')
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('chuongtrinh.index') }}">Chương trình</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('frontend.hoctap.registered_courses') }}">Học phần</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('hoctap.progress') }}">Tiến độ</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile.show') }}">Hồ sơ</a>
                            </li>
                        </ul>
                    @endif
                    @if(Auth::user()->role == 'teacher')
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('teacher.quanly') }}">Lớp học phần</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('teacher.lichgiangday') }}">Lịch giảng dạy</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('teacher.exam_schedule.index') }}">Lịch thi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('teacher.quiz') }}"> Bộ đề</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('teacher.nhapdiem.index') }}">Nhập điểm</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('teacher.assignments') }}">Xem kết quả</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile.show') }}">Hồ sơ</a>
                            </li>
                        </ul>
                    @endif
                @endauth


                    </ul>
                    <!-- /.navbar-nav -->
                    <div class="offcanvas-footer xl:hidden lg:hidden">
                        <div>
                            <a href="mailto:first.{{ $setting->email }}" class="link-inverse">{{ $setting->email }}</a>
                            <br> {{ $setting->hotline }}<br>
                            <nav class="nav social social-white mt-4">
                                <a class="text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]"
                                    href="{{ $setting->facebook }}">
                                    <img src="" class=" " />
                                </a>
                                <a class="text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]"
                                    href="{{ $setting->shopee }}">
                                    <img src="" class=" " />
                                </a>
                                <a class="text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]"
                                    href="{{ $setting->lazada }}">
                                    <img src="" class=" " />
                                </a>
                            </nav>
                            <!-- /.social -->
                        </div>
                    </div>
                    <!-- /.offcanvas-footer -->
                </div>
                <!-- /.offcanvas-body -->
            </div>
            <!-- /.navbar-collapse -->
            <div class="navbar-other w-full !flex !ml-auto">
                <ul class="navbar-nav !flex-row !items-center !ml-auto">
                <li>
    <a href="{{ route('hoctap.notifications') }}" class="relative group">
        <i class="bi 
            @if (!empty($unreadNotificationsCount) && $unreadNotificationsCount > 0)
                bi-bell-fill text-red-600 animate-pulse
            @else
                bi-bell text-gray-600
            @endif
            text-xl transition">
        </i>
        @if (!empty($unreadNotificationsCount) && $unreadNotificationsCount > 0)
            <span class="absolute top-0 right-0 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">
                {{ $unreadNotificationsCount }}
            </span>
        @endif
    </a>
</li>  
                   <li class="nav-item">
    <a class="nav-link" data-bs-toggle="offcanvas" data-bs-target="#offcanvas-user">
        <div class="d-flex align-items-center">
            <!-- Ảnh đại diện của người dùng -->
<img src="{{ Auth::check() && Auth::user()->photo ? asset(Auth::user()->photo) : asset('storage/avatars/default.jpg') }}" alt="Avatar" class="rounded-circle" width="30" height="30">
            <!-- Tên người dùng -->
            @if (Auth::check())
    <span>{{ Auth::user()->full_name }}</span>
@endif
        </div>
    </a>
</li>

                    <li class="nav-item xl:hidden lg:hidden">
                        <button class="hamburger offcanvas-nav-btn"><span></span></button>
                    </li>
                </ul>
                <!-- /.navbar-nav -->
            </div>
            <!-- /.navbar-other -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Offcanvas User -->
    <div class="offcanvas offcanvas-end bg-light" id="offcanvas-user" data-bs-scroll="true">
        <div class="offcanvas-header flex items-center justify-between p-4">
            <h3 class="mb-0">{{ Auth::check() ? 'THÔNG TIN TÀI KHOẢN' : 'ĐĂNG NHẬP' }}</h3>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Đóng"></button>
        </div>
        <div class="offcanvas-body p-4">
            @if (Auth::check())
                <div class="user-info">
                    <p><strong>Họ và tên:</strong> {{ Auth::user()->full_name }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <a href="{{ route('logout') }}" class="btn btn-danger mt-3" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            @else
                <form action="{{ route('front.login.submit') }}" method="POST">
    @csrf
    <div class="form-group mb-3">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" required>
    </div>
    <div class="form-group mb-3">
        <label for="password">Mật khẩu</label>
        <input type="password" name="password" id="password" class="form-control" required>
    </div>

    <div class="d-flex gap-2">
    <button type="submit" class="btn btn-primary flex-grow-1 text-center">Đăng nhập</button>
    <button type="button" class="btn btn-secondary flex-grow-1 text-center" onclick="window.location.href='{{ route('front.register') }}'">Đăng ký</button>
</div>
</form>
            @endif
        </div>
    </div>

    <!-- Offcanvas Search -->
    <div class="offcanvas offcanvas-top bg-light" id="offcanvas-search" data-bs-scroll="true">
        <div class="container flex !flex-row py-6">
            <form method="GET" action=""
                class="search-form relative before:content-['\eca5'] before:block before:absolute before:-translate-y-2/4 before:text-[1rem] before:text-[#343f52] before:z-[1] before:right-auto before:top-2/4 before:font-Unicons w-full before:left-0 focus:!outline-offset-0 focus:outline-0">
                <input name="searchdata" placeholder="Tìm kiếm khóa học..." id="search-form1" type="text"
                    class="form-control text-[0.8rem] !shadow-none pl-[1.75rem] !pr-[.75rem] border-0 bg-inherit m-0 block w-full font-medium leading-[1.7] text-[#60697b] px-4 py-[0.6rem] rounded-[0.4rem] focus:!outline-offset-0 focus:outline-0"
                    placeholder="Tìm kiếm khóa học, bài tập, hoặc tài nguyên">
            </form>
            
        </div>
    </div>
</header>

<style>
/* Đảm bảo ảnh đại diện là hình tròn */
.nav-link img {
    border-radius: 50%; /* Đổi ảnh thành hình tròn */
    width: 30px;  /* Đặt kích thước cho ảnh */
    height: 30px; /* Đặt kích thước cho ảnh */
    object-fit: cover; /* Đảm bảo ảnh không bị biến dạng */
}

.nav-link span {
    font-weight: 600;
    color: #333;
    margin-left: 8px; /* Khoảng cách giữa ảnh và tên */
}
    /* ===== Header Container ===== */
.wrapper.bg-soft-primary {
    background-color: #edf2fc !important;
    position: relative;
    z-index: 1000;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
}

/* ===== Navbar Styles ===== */
.navbar {
    padding: 0.8rem 0;
    transition: all 0.3s ease;
    height: 95px !important;
}

.navbar-brand img {
    max-height: 50px;
    transition: all 0.3s ease;
}

/* ===== Nav Items ===== */
.navbar-nav {
    display: flex;
    align-items: center;
}

.nav-item {
    margin: 0 0.5rem;
    position: relative;
}

.nav-link {
    color: #2c3e50;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    transition: all 0.3s ease;
    position: relative;
}



.nav-link.active {
    color: #3498db;
    font-weight: 600;
}

.nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 50%;
    transform: translateX(-50%);
    width: 60%;
    height: 3px;
    background-color: #3498db;
    border-radius: 3px;
}

/* ===== Offcanvas Styles ===== */
.offcanvas {
    background-color: #2c3e50;
}

.offcanvas-header h3 {
    color: black;
    font-weight: 600;
}

.btn-close {
    color: white;
    opacity: 0.8;
    transition: all 0.3s ease;
}



/* ===== User Dropdown ===== */
#offcanvas-user {
    width: 320px;
}

.user-info p {
    margin-bottom: 0.8rem;
    color: #4a5568;
}

.user-info strong {
    color: #2c3e50;
    font-weight: 600;
}

/* ===== Search Form ===== */
#offcanvas-search {
    height: 100px;
    display: flex;
    align-items: center;
}

.search-form {
    width: 90%;
    margin: 0 auto;
}

.search-form input {
    border: 2px solid #e2e8f0 !important;
    padding-left: 2.5rem !important;
    transition: all 0.3s ease;
}

.search-form input:focus {
    border-color: #3498db !important;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2) !important;
}

.search-form::before {
    left: 1rem !important;
    color: #7f8c8d !important;
}

/* ===== Buttons ===== */
.btn {
    padding: 0.5rem 1.2rem;
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #3498db;
    border-color: #3498db;
}



.btn-danger {
    background-color: #e74c3c;
    border-color: #e74c3c;
}

.btn-danger:hover {
    background-color: #c0392b;
    border-color: #c0392b;
}

.btn-secondary {
    background-color: #95a5a6;
    border-color: #95a5a6;
}

.btn-secondary:hover {
    background-color: #7f8c8d;
    border-color: #7f8c8d;
}

/* ===== Icons ===== */
.uil {
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.uil:hover {
    color: #3498db;
    transform: scale(1.1);
}

/* ===== Hamburger Menu ===== */
.hamburger {
    width: 30px;
    height: 24px;
    position: relative;
    transform: rotate(0deg);
    transition: .5s ease-in-out;
    cursor: pointer;
    border: none;
    background: transparent;
}

.hamburger span {
    display: block;
    position: absolute;
    height: 3px;
    width: 100%;
    background: #2c3e50;
    border-radius: 3px;
    opacity: 1;
    left: 0;
    transform: rotate(0deg);
    transition: .25s ease-in-out;
}

.hamburger span:nth-child(1) {
    top: 0px;
}

.hamburger span:nth-child(2),
.hamburger span:nth-child(3) {
    top: 10px;
}

.hamburger span:nth-child(4) {
    top: 20px;
}

.hamburger.open span:nth-child(1) {
    top: 10px;
    width: 0%;
    left: 50%;
}

.hamburger.open span:nth-child(2) {
    transform: rotate(45deg);
}

.hamburger.open span:nth-child(3) {
    transform: rotate(-45deg);
}

.hamburger.open span:nth-child(4) {
    top: 10px;
    width: 0%;
    left: 50%;
}

/* ===== Responsive Styles ===== */
@media (max-width: 992px) {
    .navbar-brand img {
        max-height: 40px;
    }
    
    .offcanvas-body {
        padding: 1rem;
    }
    
    .nav-item {
        margin: 0.3rem 0;
    }
    
    .nav-link {
        padding: 0.5rem;
    }
}

@media (max-width: 768px) {
    .navbar-other {
        justify-content: flex-end;
    }
    
    #offcanvas-search {
        height: 80px;
    }
    
    .search-form {
        width: 85%;
    }
}
</style>