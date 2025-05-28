@extends('frontend.layouts.master') 

@section('content')
    <div class="role-select-container">
        <h2>Chọn vai trò của bạn</h2>

        <form action="{{ route('luu.vai.tro') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="role">Chọn vai trò</label>
                <select name="role" id="role" class="form-control">
                    <option value="student">Sinh viên</option>
                    <option value="teacher">Giảng viên</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Lưu vai trò</button>
        </form>
    </div>
@endsection

<style>
    .role-select-container {
        max-width: 600px;
        margin: 50px auto;
        padding: 40px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        text-align: center;
    }

    h2 {
        color: #2c3e50;
        margin-bottom: 30px;
        font-weight: 600;
        font-size: 28px;
        position: relative;
        padding-bottom: 15px;
    }

    h2::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background: linear-gradient(to right, #3498db, #2ecc71);
        border-radius: 2px;
    }

    .form-group {
        margin-bottom: 30px;
        text-align: left;
    }

    label {
        display: block;
        margin-bottom: 12px;
        font-weight: 500;
        color: #4a5568;
        font-size: 16px;
    }

    .form-control {
        width: 100%;
        padding: 14px 18px;
        font-size: 16px;
        color: #2d3748;
        background-color: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        transition: all 0.3s ease;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%234a5568' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 18px center;
        background-size: 14px;
    }

    .form-control:focus {
        border-color: #3498db;
        background-color: #ffffff;
        box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
        outline: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3498db, #2ecc71);
        border: none;
        color: white;
        padding: 16px 32px;
        font-size: 16px;
        font-weight: 600;
        border-radius: 8px;
        cursor: pointer;
        width: 100%;
        transition: all 0.3s ease;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-top: 10px;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #2980b9, #27ae60);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(41, 128, 185, 0.2);
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .role-select-container {
            margin: 30px 20px;
            padding: 30px;
        }

        h2 {
            font-size: 24px;
        }

        .form-control {
            padding: 12px 16px;
        }

        .btn-primary {
            padding: 14px 28px;
        }
    }
</style>
