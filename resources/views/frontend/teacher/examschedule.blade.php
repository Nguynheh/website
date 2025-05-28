@extends('frontend.layouts.master')

@section('content')
<style>
    /* Color variables */
    :root {
        --primary: #4361ee;
        --primary-hover: #3a56d4;
        --secondary: #6c757d;
        --success: #28a745;
        --light: #f8f9fa;
        --dark: #343a40;
        --border: #dee2e6;
        --text: #495057;
        --text-light: #6c757d;
    }

    /* Main container */
    .exam-schedule-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }
    
    /* Header section */
    .schedule-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .schedule-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0;
    }
    
    /* Add schedule button */
    .add-schedule-btn {
        background-color: var(--primary);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .add-schedule-btn:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .add-schedule-btn svg {
        width: 1.25rem;
        height: 1.25rem;
    }
    
    /* Empty state */
    .empty-state {
        background-color: var(--light);
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        color: var(--text-light);
        font-size: 1.1rem;
    }
    
    /* Schedule cards grid */
    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.25rem;
        margin-top: 1.5rem;
    }
    
    /* Schedule card */
    .schedule-card {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid var(--border);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
        overflow: hidden;
    }
    
    .schedule-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: rgba(67, 97, 238, 0.2);
    }

    .schedule-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background-color: var(--primary);
    }
    
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        gap: 1rem;
    }
    
    .course-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--primary);
        margin: 0;
        line-height: 1.4;
    }
    
    .exam-date {
        font-size: 0.875rem;
        color: var(--text-light);
        background: var(--light);
        padding: 0.25rem 0.5rem;
        border-radius: 1rem;
        white-space: nowrap;
    }
    
    .card-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
        font-size: 0.9375rem;
        color: var(--text);
    }
    
    .card-details div {
        display: flex;
        gap: 0.5rem;
    }

    .card-details strong {
        color: var(--dark);
        font-weight: 500;
    }

    /* Modal styles */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .modal-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .modal-content {
        background: white;
        border-radius: 0.75rem;
        padding: 2rem;
        width: 100%;
        max-width: 500px;
        position: relative;
        transform: translateY(20px);
        transition: transform 0.3s ease;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        margin: 1rem;
    }
    
    .modal-overlay.active .modal-content {
        transform: translateY(0);
    }
    
    .modal-header {
        font-size: 1.375rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--dark);
    }
    
    .close-modal {
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
        font-size: 1.5rem;
        color: var(--text-light);
        cursor: pointer;
        transition: color 0.2s ease;
        background: none;
        border: none;
        padding: 0;
        line-height: 1;
    }
    
    .close-modal:hover {
        color: var(--dark);
    }

    /* Form styles */
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--dark);
        font-size: 0.9375rem;
    }
    
    .form-select, .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border);
        border-radius: 0.5rem;
        background-color: white;
        transition: all 0.2s ease;
        font-size: 0.9375rem;
        color: var(--text);
    }
    
    .form-select:focus, .form-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
    }
    
    .submit-btn {
        background-color: var(--primary);
        color: white;
        padding: 0.875rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        width: 100%;
        font-size: 1rem;
        margin-top: 0.5rem;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
    }
    
    .submit-btn:hover {
        background-color: var(--primary-hover);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .submit-btn svg {
        width: 1.25rem;
        height: 1.25rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .schedule-grid {
            grid-template-columns: 1fr;
        }
        
        .schedule-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .modal-content {
            padding: 1.5rem;
        }

        .modal-header {
            font-size: 1.25rem;
            margin-bottom: 1.25rem;
        }
    }

    @media (max-width: 480px) {
        .exam-schedule-container {
            padding: 0 0.75rem;
        }

        .schedule-title {
            font-size: 1.5rem;
        }

        .add-schedule-btn {
            width: 100%;
            justify-content: center;
        }

        .card-details {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="exam-schedule-container">
    <div class="schedule-header">
        <h1 class="schedule-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            Lịch thi đã đăng ký
        </h1>
        <button onclick="openModal()" class="add-schedule-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Đăng ký lịch thi
        </button>
    </div>

    @if($lichthis->isEmpty())
        <div class="empty-state">
            <p>Chưa có lịch thi nào được đăng ký</p>
        </div>
    @else
        <div class="schedule-grid">
            @foreach($lichthis as $lt)
                <div class="schedule-card">
                    <div class="card-header">
                        <h3 class="course-title">{{ $lt->phancong->hocphan->title ?? '---' }}</h3>
                        <div class="exam-date">
                            {{ \Carbon\Carbon::parse($lt->ngay1)->format('d/m/Y') }}
                            @if($lt->ngay2)
                                – {{ \Carbon\Carbon::parse($lt->ngay2)->format('d/m/Y') }}
                            @endif
                        </div>
                    </div>
                    <div class="card-details">
                        <div>
                            <strong>Buổi:</strong>
                            <span>{{ $lt->buoi }}</span>
                        </div>
                        <div>
                            <strong>Địa điểm:</strong>
                            <span>{{ $lt->diadiem->title ?? '---' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Modal -->
<div id="modal" class="modal-overlay">
    <div class="modal-content">
        <button class="close-modal" onclick="closeModal()">&times;</button>
        <h2 class="modal-header">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg>
            Đăng ký lịch thi
        </h2>

        <form action="{{ route('teacher.exam_schedule.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Học phần -->
            <div class="form-group">
                <label for="phancong_id" class="form-label">Học phần</label>
                <select name="phancong_id" id="phancong_id" class="form-select" required>
                    <option value="">-- Chọn học phần --</option>
                    @foreach ($phancongs as $pc)
                        <option value="{{ $pc->id }}">
                            {{ $pc->hocphan->title ?? '---' }} - {{ $pc->hocky->so_hoc_ky ?? '' }} ({{ $pc->namhoc->nam_hoc ?? '' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Buổi -->
            <div class="form-group">
                <label for="buoi" class="form-label">Buổi thi</label>
                <select name="buoi" id="buoi" class="form-select" required>
                    <option value="Sáng">Sáng</option>
                    <option value="Chiều">Chiều</option>
                    <option value="Tối">Tối</option>
                </select>
            </div>

            <!-- Ngày -->
            <div class="form-group">
                <label for="ngay1" class="form-label">Ngày thi lần 1</label>
                <input type="date" name="ngay1" id="ngay1" class="form-input" required>
            </div>

            <div class="form-group">
                <label for="ngay2" class="form-label">Ngày thi lần 2 (nếu có)</label>
                <input type="date" name="ngay2" id="ngay2" class="form-input">
            </div>

            <!-- Địa điểm -->
            <div class="form-group">
                <label for="dia_diem_thi" class="form-label">Địa điểm thi</label>
                <select name="dia_diem_thi" id="dia_diem_thi" class="form-select" required>
                    <option value="">-- Chọn địa điểm --</option>
                    @foreach ($diadiems as $d)
                        <option value="{{ $d->id }}">{{ $d->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Submit -->
            <div class="pt-2">
                <button type="submit" class="submit-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                        <polyline points="7 3 7 8 15 8"></polyline>
                    </svg>
                    Lưu lịch thi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
        document.getElementById('modal').classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Close modal when clicking outside
    document.getElementById('modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endsection