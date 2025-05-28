<footer class="footer">
    <div class="container">
        <div class="row">
            <!-- Cột 1: Logo và Thông tin hệ thống -->
            <div class="col-md-4">
                <div class="footer-logo">
                    <img src="{{ asset('storage/avatar/logo.png') }}" alt="Logo LMS"
                         class="h-36 md:h-48 w-auto object-contain transition duration-300 hover:opacity-80">
                    <p class="footer-description">Hệ thống quản lý học tập LMS - Giúp bạn học tập hiệu quả hơn, mọi lúc, mọi nơi.</p>
                </div>
            </div>

            <!-- Cột 2: Liên kết nhanh -->
            <div class="col-md-4">
                <div class="footer-links">
                    <h5>Liên kết nhanh</h5>
                    <ul class="footer-links">
                        <li><a href="/about">Giới thiệu</a></li>
                        <li><a href="/courses">Học phần</a></li>
                        <li><a href="/schedule">Lịch học</a></li>
                        <li><a href="/support">Hỗ trợ</a></li>
                    </ul>
                </div>
            </div>

            <!-- Cột 3: Thông tin liên hệ -->
            <div class="col-md-4">
                <div class="footer-contact">
                    <h5>Liên hệ</h5>
                    <ul>
                        <li>Email: <a href="mailto:support@example.com">support@example.com</a></li>
                        <li>Phone: <a href="tel:+1234567890">+1 234 567 890</a></li>
                        <li>Địa chỉ: 123, Đường XYZ, Thành phố ABC</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
.footer {
    background-color: #5dade2; /* Xanh nhạt */
    color: white;
    padding: 40px 0;
}

.footer .container {
    max-width: 1200px;
    margin: 0 auto;
}

.footer-logo img {
    max-width: 150px;
}

.footer-description {
    font-size: 14px;
    margin-top: 10px;
    color: #eaf2f8;
}

.footer h5 {
    font-size: 16px;
    color: #ffffff;
    margin-bottom: 15px;
}

.footer-links,
.footer-contact {
    list-style-type: none;
    padding-left: 0;
}

.footer-links li,
.footer-contact li {
    margin: 8px 0;
}

.footer-links a,
.footer-contact a {
    color: #fdfefe;
    text-decoration: none;
    font-size: 14px;
    transition: color 0.2s;
}

.footer-links a:hover,
.footer-contact a:hover {
    color: #d6eaf8;
    text-decoration: underline;
}

.footer-bottom {
    background-color: #aed6f1; /* Xanh nhạt hơn */
    padding: 15px 0;
}

.footer-bottom p {
    font-size: 14px;
    color: #154360;
}

/* Responsive layout */
@media (max-width: 767px) {
    .footer .row {
        flex-direction: column;
        align-items: center;
    }

    .footer .col-md-4 {
        width: 100%;
        margin-bottom: 20px;
    }
}
</style>
