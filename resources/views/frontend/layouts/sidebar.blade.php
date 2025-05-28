<ul class="nav flex-column sidebar">
    <!-- Các mục cho sinh viên -->
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('frontend.hoctap.registered_courses') ? 'active' : '' }}"
           href="{{ route('frontend.hoctap.registered_courses') }}">
           📚 Học phần
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('thoikhoabieu.tuan') ? 'active' : '' }}"
           href="{{ route('thoikhoabieu.tuan') }}">
           🗓️ Lịch học
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('hoctap.lichhoc') ? 'active' : '' }}"
           href="{{ route('hoctap.lichhoc') }}">
           📝 Lịch thi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('hoctap.baitap.dalam') ? 'active' : '' }}"
           href="{{ route('hoctap.baitap.dalam') }}">
           📂 Bài tập hoàn thành
        </a>
    </li>


<style>
/* Tổng thể sidebar */
.sidebar {
    background-color: #eef4f8;
    min-height: 100vh;
    padding: 1.5rem 1rem;
    border-right: 1px solid #d1dce5;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.03);
}

/* Khoảng cách giữa các item */
.nav.flex-column .nav-item {
    margin-bottom: 0.75rem;
}

/* Mặc định link */
.nav.flex-column .nav-link {
    color: #2c3e50;
    font-weight: 500;
    padding: 0.65rem 1rem;
    border-radius: 10px;
    transition: all 0.2s ease-in-out;
    display: block;
    background-color: transparent;
}

/* Hover */
.nav.flex-column .nav-link:hover {
    background-color: #dbeafe;
    color: #1d4ed8;
    text-decoration: none;
    transform: translateX(3px);
}

/* Active */
.nav.flex-column .nav-link.active {
    background-color: #93c5fd;
    color: #0f172a;
    font-weight: 600;
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        padding: 1rem;
        border-right: none;
        border-bottom: 1px solid #d1dce5;
    }
}
</style>
