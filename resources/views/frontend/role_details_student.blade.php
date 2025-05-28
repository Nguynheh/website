@extends('frontend.layouts.master')

@section('content')
    <div class="container">
        <h2>Chọn vai trò: Sinh viên</h2>

        <form action="{{ route('luu.thong.tin') }}" method="POST">
        @csrf
            <div class="form-group">
                <label for="role">Chọn vai trò</label>
                <input type="text" name="role" id="role" value="student" hidden>
            </div>

            <div class="form-group">
                <label for="donvi_id">Đơn vị</label>
                <select name="donvi_id" id="donvi_id" class="form-control">
                    @foreach($donviList as $donvi)
                        <option value="{{ $donvi->id }}">{{ $donvi->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="nganh_id">Ngành</label>
                <select name="nganh_id" id="nganh_id" class="form-control">
                    @foreach($nganhList as $nganh)
                        <option value="{{ $nganh->id }}">{{ $nganh->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="class_id">Lớp</label>
                <select name="class_id" id="class_id" class="form-control">
                    @foreach($classList as $class)
                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Lưu vai trò</button>
        </form>
    </div>
@endsection

    <style>
    

h2 {
    color: #2c3e50;
    text-align: center;
    margin-bottom: 25px;
    font-weight: 600;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #495057;
}

.form-control {
    width: 100%;
    padding: 10px 15px;
    font-size: 16px;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 4px;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn {
    display: inline-block;
    font-weight: 400;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    user-select: none;
    border: 1px solid transparent;
    padding: 10px 20px;
    font-size: 16px;
    line-height: 1.5;
    border-radius: 4px;
    transition: all 0.15s ease-in-out;
    cursor: pointer;
}

.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
    width: 100%;
    margin-top: 10px;
}

.btn-primary:hover {
    background-color: #0069d9;
    border-color: #0062cc;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .container {
        margin: 15px;
        padding: 15px;
    }
}
    </style>
