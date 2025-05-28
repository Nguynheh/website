<!-- Dashboard Overview -->
<section class="dashboard-overview mb-5">
    <div class="image-container">
        <img src="{{ asset('storage/avatar/screenshot_1747244962.png') }}" alt="banner" class="img-fluid w-100 rounded-3">
    </div>
    <div class="text-center">
        <h2 class="fw-bold mb-4 text-gradient">Tổng Quan Hệ Thống</h2>
    </div>
    
    <!-- Wrapper cuộn ngang -->
    <div class="scrolling-wrapper d-flex flex-nowrap overflow-auto px-2">
        <!-- Card: Tổng số học phần -->
        <div class="card stat-card flex-shrink-0 mx-2">
            <div class="card-body text-center p-4">
                <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-circle mx-auto mb-3">
                    <i class="fas fa-book fa-2x"></i>
                </div>
                <h5 class="fw-bold">Học phần</h5>
                <h3 class="fw-bold text-primary">{{ $totalHocPhan }}</h3>
            </div>
        </div>

        <!-- Card: Tổng số sinh viên -->
        <div class="card stat-card flex-shrink-0 mx-2">
            <div class="card-body text-center p-4">
                <div class="icon-box bg-success bg-opacity-10 text-success rounded-circle mx-auto mb-3">
                    <i class="fas fa-users fa-2x"></i>
                </div>
                <h5 class="fw-bold">Sinh viên</h5>
                <h3 class="fw-bold text-success">{{ $totalStudents }}</h3>
            </div>
        </div>

        <!-- Card: Trắc nghiệm -->
        <div class="card stat-card flex-shrink-0 mx-2">
            <div class="card-body text-center p-4">
                <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-circle mx-auto mb-3">
                    <i class="fas fa-question-circle fa-2x"></i>
                </div>
                <h5 class="fw-bold">Đề trắc nghiệm</h5>
                <h3 class="fw-bold text-warning">{{ $totalTracNghiem }}</h3>
            </div>
        </div>

        <!-- Card: Tự luận -->
        <div class="card stat-card flex-shrink-0 mx-2">
            <div class="card-body text-center p-4">
                <div class="icon-box bg-danger bg-opacity-10 text-danger rounded-circle mx-auto mb-3">
                    <i class="fas fa-pen-fancy fa-2x"></i>
                </div>
                <h5 class="fw-bold">Đề tự luận</h5>
                <h3 class="fw-bold text-danger">{{ $totalTuLuan }}</h3>
            </div>
        </div>

        <!-- Card: Giảng viên -->
        <div class="card stat-card flex-shrink-0 mx-2">
            <div class="card-body text-center p-4">
                <div class="icon-box bg-secondary bg-opacity-10 text-secondary rounded-circle mx-auto mb-3">
                    <i class="fas fa-chalkboard-teacher fa-2x"></i>
                </div>
                <h5 class="fw-bold">Giảng viên</h5>
                <h3 class="fw-bold text-secondary">{{ $totalGiangVien }}</h3>
            </div>
        </div>
    </div>
</section>

<!-- Inspiration Carousel -->
<div class="container mb-5">
    <h2 class="text-center fw-bold mb-4 text-gradient">Truyền Cảm Hứng Mỗi Ngày</h2>
    <div id="quoteCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner bg-light p-4 rounded shadow">
            <div class="carousel-item active text-center">
                <blockquote class="blockquote">
                    <p class="mb-0 fs-4">"Giáo dục là vũ khí mạnh nhất để thay đổi thế giới." – Nelson Mandela</p>
                </blockquote>
            </div>
            <div class="carousel-item text-center">
                <blockquote class="blockquote">
                    <p class="mb-0 fs-4">"Thành công không phải là chìa khóa của hạnh phúc. Hạnh phúc là chìa khóa của thành công." – Albert Schweitzer</p>
                </blockquote>
            </div>
            <div class="carousel-item text-center">
                <blockquote class="blockquote">
                    <p class="mb-0 fs-4">"Học tập là hành trình không bao giờ kết thúc." – Albert Einstein</p>
                </blockquote>
            </div>
        </div>
    </div>
</div>

<!-- Daily Study Tip -->
<div class="container mb-5">
    <div class="alert alert-info shadow-sm rounded-3 text-center fs-5">
        💡 <strong>Mẹo học tập hôm nay:</strong> Sử dụng phương pháp Pomodoro: học 25 phút, nghỉ 5 phút để tăng hiệu quả!
    </div>
</div>

<!-- News Section -->
<div class="container">
    <h2 class="text-center fw-bold mb-4 text-gradient">Bảng Tin Tức Hệ Thống Quản Lý Học Tập (LMS)</h2>

    <!-- News Items -->
    <div class="row">
        <!-- News Item 1 -->
       <style>
    .news-card {
        transition: box-shadow 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }

    .news-card:hover {
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .news-card img {
        object-fit: cover;
        height: 200px;
        width: 100%;
    }

    .news-card .card-title a {
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s, text-decoration 0.3s;
    }

    .news-card .card-title a:hover {
        color: #007bff;
        text-decoration: underline;
    }

    .news-card .card-text {
        color: #555;
        font-size: 0.95rem;
        line-height: 1.5;
    }
</style>

<!-- News Item 1 -->
<div class="col-md-4 mb-4">
    <div class="card shadow-sm border-0 news-card">
        <img src="{{ asset('storage/picture.png') }}" class="card-img-top" alt="news-image">
        <div class="card-body">
            <h5 class="card-title">
                <a href="https://www.reuters.com/markets/deals/kkr-take-edu-tech-firm-instructure-private-48-billion-2024-07-25/?utm_source=chatgpt.com" 
                   target="_blank" class="text-dark">
                   Instructure được KKR mua lại với giá 4,8 tỷ USD
                </a>
            </h5>
            <p class="card-text">
                Ngày 25/7/2024, công ty phần mềm giáo dục Instructure Holdings đã đồng ý bán cho KKR với giá 4,8 tỷ USD, tương đương 23,60 USD/cổ phiếu...
            </p>
        </div>
    </div>
</div>

<!-- News Item 2 -->
<div class="col-md-4 mb-4">
    <div class="card shadow-sm border-0 news-card">
        <img src="{{ asset('storage/picture1.png') }}" class="card-img-top" alt="news-image">
        <div class="card-body">
            <h5 class="card-title">
                <a href="https://www.theaustralian.com.au/business/technology/big-techs-plan-to-reverse-australian-universities-slide-down-global-rankings/news-story/5e20a09a77be107b46b6cb5fa446f4ae?utm_source=chatgpt.com" 
                   target="_blank" class="text-dark">
                   Workday giới thiệu nền tảng AI cho giáo dục tại Úc
                </a>
            </h5>
            <p class="card-text">
                Để cải thiện trải nghiệm sinh viên và giảm chi phí quản lý, Workday đã ra mắt nền tảng AI Workday Student tại Úc...
            </p>
        </div>
    </div>
</div>

<!-- News Item 3 -->
<div class="col-md-4 mb-4">
    <div class="card shadow-sm border-0 news-card">
        <img src="{{ asset('storage/picture2.png') }}" class="card-img-top" alt="news-image">
        <div class="card-body">
            <h5 class="card-title">
                <a href="https://www.theaustralian.com.au/nation/us-education-support-company-chegg-takes-australian-uni-regulator-to-court/news-story/ebb02170f542a8d38133c6b354b515eb?utm_source=chatgpt.com" 
                   target="_blank" class="text-dark">
                   Chegg kiện cơ quan quản lý giáo dục Úc
                </a>
            </h5>
            <p class="card-text">
                Công ty hỗ trợ học trực tuyến Chegg đã kiện Cơ quan Tiêu chuẩn và Chất lượng Giáo dục Đại học Úc (TEQSA) vì cho rằng họ bị đối xử không công bằng...
            </p>
        </div>
    </div>
</div>
    </div>

   <div class="trends-section">
    <h3 class="text-center fw-bold mb-3">Xu Hướng LMS Nổi Bật Năm 2024</h3>

    <ul class="list-group">
        <li class="list-group-item">
            <strong>AI và cá nhân hóa học tập:</strong>
            Trí tuệ nhân tạo đang được tích hợp vào LMS để đề xuất nội dung học phù hợp với từng người dùng, giúp nâng cao hiệu quả học tập.
            <a href="https://syndelltech.com/latest-learning-management-system-trends/?utm_source=chatgpt.com" target="_blank">Xem chi tiết</a>
        </li>
        <li class="list-group-item">
            <strong>Học tập trải nghiệm với VR/AR:</strong>
            Công nghệ thực tế ảo và thực tế tăng cường đang được sử dụng để tạo ra môi trường học tập mô phỏng, giúp sinh viên trải nghiệm thực tế trong các tình huống học tập.
            <a href="https://www.immersivelearning.news/2024/03/12/the-future-of-learning-management-systems-nextgen-tools-from-vr-ar-to-ai-driven-teacher-assistants/?utm_source=chatgpt.com" target="_blank">Xem chi tiết</a>
        </li>
        <li class="list-group-item">
            <strong>Phân tích dữ liệu và học tập dựa trên dữ liệu:</strong>
            Các nền tảng LMS hiện đại sử dụng phân tích dữ liệu để theo dõi tiến độ học tập của sinh viên, từ đó điều chỉnh phương pháp giảng dạy phù hợp.
            <a href="https://research.com/education/lms-trends?utm_source=chatgpt.com" target="_blank">Xem chi tiết</a>
        </li>
    </ul>
</div>
</div>

<style>
    .carousel-inner .carousel-item {
        transition: transform 1s ease-in-out;
    }

    .carousel-item blockquote {
        font-style: italic;
        color: #555;
        padding: 20px;
    }

    .alert-info {
        background-color: #e6f7ff;
        color: #0056b3;
    }

    .alert-info strong {
        font-size: 1.1rem;
    }

    .text-gradient {
        background: -webkit-linear-gradient(#ff7e5f, #feb47b);
        -webkit-background-clip: text;
        color: transparent;
    }

    .card-body {
        padding: 2rem;
    }

    .container {
        margin-top: 20px;
    }

    .carousel {
        border-radius: 10px;
    }

    .scrolling-wrapper {
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
    }

    .stat-card {
        width: 250px;
        scroll-snap-align: start;
    }

    .news-item a {
        color: #007bff;
        text-decoration: none;
    }

    .news-item a:hover {
        text-decoration: underline;
    }
    
</style>
