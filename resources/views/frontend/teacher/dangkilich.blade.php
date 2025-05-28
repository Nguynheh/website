@extends('frontend.layouts.master')

@section('content')
<style>
    .schedule-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2.5rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .schedule-title {
        color: #2d3748;
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #4a5568;
        font-size: 0.95rem;
    }
    
    .form-select, .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background-color: #f8fafc;
        transition: all 0.2s ease;
        font-size: 0.95rem;
    }
    
    .form-select:focus, .form-input:focus {
        outline: none;
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
        background-color: white;
    }
    
    .grid-cols-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }
    
    .submit-btn {
        width: 100%;
        padding: 0.875rem;
        background-color: #4299e1;
        color: white;
        font-weight: 500;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-top: 1rem;
        font-size: 1rem;
    }
    
    .submit-btn:hover {
        background-color: #3182ce;
        transform: translateY(-1px);
    }
    
    .submit-btn:active {
        transform: translateY(0);
    }
    
    .alert-success {
        background-color: #48bb78;
        color: white;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
    }
    
    @media (max-width: 640px) {
        .schedule-container {
            padding: 1.5rem;
        }
        
        .grid-cols-2 {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
    }
</style>

<div class="schedule-container">
    <h1 class="schedule-title">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        Đăng ký lịch giảng dạy
    </h1>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('teacher.tkb.store') }}" method="POST">
        @csrf
        <div class="space-y-6">
            <!-- Chọn phân công -->
            <div class="form-group">
                <label for="phancong_id" class="form-label">Phân công giảng dạy</label>
                <select name="phancong_id" id="phancong_id" class="form-select" required>
                    <option value="">Chọn phân công</option>
                    @foreach($phancongs as $phancong)
                        <option value="{{ $phancong->id }}">{{ $phancong->hocphan->title ?? '---' }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Chọn địa điểm -->
            <div class="form-group">
                <label for="diadiem_id" class="form-label">Địa điểm</label>
                <select name="diadiem_id" id="diadiem_id" class="form-select" required>
                    <option value="">Chọn địa điểm</option>
                    @foreach($diadiems as $diadiem)
                        <option value="{{ $diadiem->id }}">{{ $diadiem->title ?? '---' }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Chọn ngày -->
            <div class="form-group">
                <label for="ngay" class="form-label">Ngày giảng dạy</label>
                <input type="date" name="ngay" id="ngay" class="form-input" required>
            </div>

            <!-- Chọn buổi -->
            <div class="form-group">
                <label for="buoi" class="form-label">Buổi học</label>
                <select name="buoi" id="buoi" class="form-select" required>
                    <option value="">Chọn buổi</option>
                    <option value="Sáng">Sáng</option>
                    <option value="Chiều">Chiều</option>
                    <option value="Tối">Tối</option>
                </select>
            </div>

            <!-- Chọn tiết -->
            <div class="grid-cols-2">
                <div class="form-group">
                    <label for="tietdau" class="form-label">Tiết bắt đầu</label>
                    <select name="tietdau" id="tietdau" class="form-select" required>
                        <option value="">Chọn tiết</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tietcuoi" class="form-label">Tiết kết thúc</label>
                    <select name="tietcuoi" id="tietcuoi" class="form-select" required>
                        <option value="">Chọn tiết</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="submit-btn">
                Đăng ký lịch giảng dạy
            </button>
        </div>
    </form>
</div>

<script>
    // Lấy các phần tử buổi và tiết
    const buoiSelect = document.getElementById('buoi');
    const tietdauSelect = document.getElementById('tietdau');
    const tietcuoiSelect = document.getElementById('tietcuoi');
    
    // Dữ liệu tiết học cho từng buổi
    const tietHocOptions = {
        'Sáng': [1, 2, 3, 4],
        'Chiều': [7, 8, 9, 10],
        'Tối': [13, 14, 15, 16]
    };
    
    // Hàm cập nhật tiết học
    function updateTietHoc() {
        const selectedBuoi = buoiSelect.value;
    
        // Xóa các tùy chọn cũ
        tietdauSelect.innerHTML = '<option value="">Chọn tiết</option>';
        tietcuoiSelect.innerHTML = '<option value="">Chọn tiết</option>';
    
        if (tietHocOptions[selectedBuoi]) {
            tietHocOptions[selectedBuoi].forEach(tiet => {
                const option1 = document.createElement('option');
                option1.value = tiet;
                option1.textContent = tiet;
                tietdauSelect.appendChild(option1);
    
                const option2 = document.createElement('option');
                option2.value = tiet;
                option2.textContent = tiet;
                tietcuoiSelect.appendChild(option2);
            });
        }
    }
    
    // Gắn sự kiện thay đổi cho buổi học
    buoiSelect.addEventListener('change', updateTietHoc);
    
    // Cập nhật tiết học ban đầu
    updateTietHoc();
</script>
@endsection