@extends('frontend.layouts.master')

@section('content')
<style>
    .edit-assignment-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 2.5rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .edit-assignment-title {
        color: #2d3748;
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .form-section {
        margin-bottom: 1.75rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #4a5568;
        font-size: 0.95rem;
    }
    
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background-color: #f8fafc;
        transition: all 0.2s ease;
        font-size: 0.95rem;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #4299e1;
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
        background-color: white;
    }
    
    textarea.form-control {
        min-height: 120px;
    }
    
    .alert-danger {
        background-color: #fff5f5;
        color: #e53e3e;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border-left: 4px solid #e53e3e;
    }
    
    .alert-danger ul {
        margin-bottom: 0;
        padding-left: 1.25rem;
    }
    
    .resources-list {
        margin-top: 0.5rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        background-color: #f8fafc;
    }
    
    .resources-list ul {
        margin-bottom: 0;
        padding-left: 0;
        list-style: none;
    }
    
    .resources-list li {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid #edf2f7;
    }
    
    .resources-list li:last-child {
        border-bottom: none;
    }
    
    .resource-link {
        color: #4299e1;
        text-decoration: none;
        flex-grow: 1;
    }
    
    .resource-link:hover {
        text-decoration: underline;
    }
    
    .delete-resource-btn {
        background-color: #e53e3e;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.25rem 0.75rem;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .delete-resource-btn:hover {
        background-color: #c53030;
    }
    
    .submit-btn {
        background-color: #4299e1;
        color: white;
        font-weight: 500;
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .submit-btn:hover {
        background-color: #3182ce;
        transform: translateY(-1px);
    }
    
    /* Tom Select customization */
    .ts-control {
        border: 1px solid #e2e8f0 !important;
        background-color: #f8fafc !important;
        border-radius: 8px !important;
        padding: 0.5rem 1rem !important;
    }
    
    .ts-dropdown {
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    }
    
    .ts-wrapper.multi .ts-control > div {
        background-color: #4299e1 !important;
        color: white !important;
        border-radius: 6px !important;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .edit-assignment-container {
            padding: 1.5rem;
        }
    }
</style>

<div class="edit-assignment-container">
    <h1 class="edit-assignment-title">Thêm bài tập vào học phần</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('teacher.edit_noidung.update', $noidungPhancong->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Chọn Phân công -->
        <!-- Phân công giảng dạy (chỉ hiển thị, không cho chọn lại) -->
<div class="form-section">
    <label class="form-label">Phân công giảng dạy</label>
    <div style="padding: 0.5rem 1rem; background-color: #edf2f7; border-radius: 6px; font-size: 0.95rem;">
        {{ $noidungPhancong->phancong->giangvien->user->full_name }} - {{ $noidungPhancong->phancong->hocphan->title }}
    </div>
    <input type="hidden" name="phancong_id" value="{{ $noidungPhancong->phancong_id }}">
</div>


        <!-- Tiêu đề -->
        <div class="form-section">
    <label for="title" class="form-label">Tiêu đề</label>
    <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $noidungPhancong->title) }}" readonly>
</div>

<!-- Nội dung -->
<div class="form-section">
    <label for="content" class="form-label">Nội dung</label>
    <textarea class="form-control" id="content" name="content" rows="4" readonly>{{ old('content', $noidungPhancong->content) }}</textarea>
</div>

<select id="tuluan" name="tuluan_ids[]" multiple class="form-select" size="5">
    @foreach ($bode_tuluans as $bodetuluan)
        <option value="{{ $bodetuluan->id }}" 
            @if(in_array((int)$bodetuluan->id, $tuluan_ids)) selected @endif>
            {{ $bodetuluan->title }}
        </option>
    @endforeach
</select>

<div class="form-section">
    <label for="tracnghiem" class="form-label">Bộ đề Trắc nghiệm</label>
    <select id="tracnghiem" name="tracnghiem_ids[]" multiple class="form-select" size="5">
        @foreach ($bode_tracnghiems as $bode)
            <option value="{{ $bode->id }}" 
                @if(in_array((int)$bode->id, $tracnghiem_ids)) selected @endif>
                {{ $bode->title }}
            </option>
        @endforeach
    </select>
</div>




        <div class="text-right">
            <button type="submit" class="submit-btn">Cập nhật nội dung</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/js/tom-select.complete.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">

<script>
document.addEventListener("DOMContentLoaded", function () {
    const config = {
        create: true,
        persist: false,
        highlight: true,
        plugins: ['remove_button'],
        hideSelected: true,
        closeAfterSelect: false,
        allowEmptyOption: false,
        maxItems: null,
        render: {
            item: function(data, escape) {
                return `<div class="ts-item">${escape(data.text)}</div>`;
            },
            no_results: function(data, escape) {
                return '<div class="no-results">Không tìm thấy kết quả phù hợp</div>';
            }
        },
        sortField: {
            field: "text",
            direction: "asc"
        }
    };

    // Áp dụng TomSelect cho từng trường
    new TomSelect("#tuluan", {
        ...config,
        placeholder: "Chọn bộ đề tự luận..."
    });

    new TomSelect("#tracnghiem", {
        ...config,
        placeholder: "Chọn bộ đề trắc nghiệm..."
    });

    // Xử lý nút Xóa tài nguyên
    document.querySelectorAll('.delete-resource-btn').forEach(button => {
        button.addEventListener('click', function () {
            const url = this.getAttribute('data-url');
            const name = this.getAttribute('data-name');

            if (confirm(`Bạn có chắc chắn muốn xóa tài nguyên "${name}"?`)) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === 'Tài nguyên đã được xóa.') {
                        location.reload();
                    } else {
                        alert('Xóa thất bại.');
                    }
                })
                .catch(error => {
                    console.error('Lỗi khi gửi request:', error);
                    alert('Đã xảy ra lỗi khi xóa.');
                });
            }
        });
    });
});
</script>

@endsection