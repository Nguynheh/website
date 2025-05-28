<style>
    /* Main container */
    .assignment-management-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        max-width: 1200px;
        margin: 2rem auto;
        padding: 2rem;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    }

    /* Title styling */
    .management-title {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 1.8rem;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.8rem;
    }

    /* Create button */
    .create-btn {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.8rem 1.5rem;
        font-size: 1rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(52, 152, 219, 0.2);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .create-btn:hover {
        background: linear-gradient(135deg, #2980b9, #3498db);
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(52, 152, 219, 0.3);
    }

    /* Table styling */
    .assignment-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 1.5rem;
    }

    /* Header styling */
    .table-header {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        color: white;
    }

    .table-header th {
        padding: 1rem;
        font-weight: 500;
        text-align: left;
    }

    /* Table cells */
    .assignment-table td {
        padding: 1rem;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .assignment-table tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    .assignment-table tr:hover {
        background-color: #f1f7fe;
    }

    /* Button styling */
    .action-btn {
        border: none;
        border-radius: 4px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        margin-right: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .edit-btn {
        background-color: #f39c12;
        color: white;
    }

    .edit-btn:hover {
        background-color: #e67e22;
    }

    .view-btn {
        background-color: #3498db;
        color: white;
    }

    .view-btn:hover {
        background-color: #2980b9;
    }

    .delete-btn {
        background-color: #e74c3c;
        color: white;
    }

    .delete-btn:hover {
        background-color: #c0392b;
    }

    /* Responsive adjustments */
    @media (max-width: 992px) {
        .assignment-management-container {
            padding: 1.5rem;
        }
        
        .assignment-table {
            display: block;
            overflow-x: auto;
        }
        
        .action-btns {
            display: flex;
            flex-wrap: wrap;
        }
        
        .action-btn {
            margin-bottom: 0.5rem;
        }
    }
</style>

@extends('frontend.layouts.master')
@section('content')
<div class="container assignment-management-container">
    <h4 class="management-title">
        <span>📚</span>
        Quản lý bài tập cho học phần
    </h4>

    <!-- Tạo bài tập mới -->
    <div class="mb-4">
        <a href="{{ route('frontend.teacher.assignments.create') }}" class="create-btn">
            <span>+</span>
            <span>Tạo bài tập mới</span>
        </a>
    </div>

    <!-- Danh sách bài tập đã giao -->
    <table class="table assignment-table">
        <thead class="table-header">
            <tr>
                <th>STT</th>
                <th>Học phần</th>
                <th>Loại bài tập</th>
                <th>Ngày giao</th>
                <th>Hạn nộp</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($assignments as $assignment)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $assignment->hocphan->title }}</td>
                <td>{{ $assignment->quiz_type == 'trac_nghiem' ? 'Trắc nghiệm' : 'Tự luận' }}</td>
                <td>{{ $assignment->assigned_at->format('d/m/Y H:i') }}</td>
                <td>{{ $assignment->due_date ? $assignment->due_date->format('d/m/Y H:i') : 'Không có' }}</td>
                <td>
                    <div class="action-btns">
                        <!-- Nút sửa bài tập -->
                        <a href="{{ route('frontend.teacher.editassignment', $assignment->id) }}" class="action-btn edit-btn">Sửa</a>

                        <!-- Nút xem chi tiết bài tập -->
                        <a href="{{ route('frontend.teacher.submissions_list', $assignment->id) }}" class="action-btn view-btn">Xem bài nộp</a>

                        <!-- Nút xóa bài tập -->
                        <form action="{{ route('frontend.teacher.assignments.destroy', $assignment->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete-btn">Xóa</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection