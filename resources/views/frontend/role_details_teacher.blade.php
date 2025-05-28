@extends('frontend.layouts.master')

@section('content')
    <div class="container">
        <h2>Chọn vai trò: Giảng viên</h2>

        <form action="{{ route('luu.thong.tin') }}" method="POST">
            @csrf
            <input type="hidden" name="role" value="teacher">

            <div class="form-group">
                <label for="mgv">Mã giảng viên</label>
                <input type="text" name="mgv" id="mgv" class="form-control" placeholder="Mã giảng viên" required>
            </div>

            <div class="form-group">
                <label for="ma_donvi">Đơn vị</label>
                <select name="ma_donvi" id="ma_donvi" class="form-control" required>
                    @foreach($donviList as $donvi)
                        <option value="{{ $donvi->id }}">{{ $donvi->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="chuyen_nganh">Chuyên ngành</label>
                <select name="chuyen_nganh" id="chuyen_nganh" class="form-control" required>
                    @foreach($nganhList as $nganh)
                        <option value="{{ $nganh->id }}">{{ $nganh->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="hoc_ham">Học hàm</label>
                <input type="text" name="hoc_ham" id="hoc_ham" class="form-control" placeholder="VD: PGS, GS">
            </div>

            <div class="form-group">
                <label for="hoc_vi">Học vị</label>
                <input type="text" name="hoc_vi" id="hoc_vi" class="form-control" placeholder="VD: Thạc sĩ, Tiến sĩ">
            </div>

            <div class="form-group">
                <label for="loai_giangvien">Loại giảng viên</label>
                <select name="loai_giangvien" id="loai_giangvien" class="form-control">
                        <option value="">Chọn loại giảng viên</option>
                        <option value="1" {{ old('loai_giangvien') == '1' ? 'selected' : '' }}>Giảng viên hạng 1</option>
                        <option value="2" {{ old('loai_giangvien') == '2' ? 'selected' : '' }}>Giảng viên hạng 2</option>
                        <option value="3" {{ old('loai_giangvien') == '3' ? 'selected' : '' }}>Giảng viên hạng 3</option>
                        <option value="4" {{ old('loai_giangvien') == '4' ? 'selected' : '' }}>Giảng viên hạng 4</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Lưu thông tin</button>
        </form>
    </div>
@endsection
<style>
    
h2 {
    color: #2c3e50;
    text-align: center;
    margin-bottom: 30px;
    font-weight: 600;
    position: relative;
    padding-bottom: 15px;
}

h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 3px;
    background: linear-gradient(to right, #3498db, #2ecc71);
}

.form-group {
    margin-bottom: 25px;
}

label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #34495e;
    font-size: 15px;
}

.form-control {
    width: 100%;
    padding: 12px 15px;
    font-size: 15px;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
}

select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 12px;
    padding-right: 30px;
}

.btn-primary {
    background: linear-gradient(135deg, #3498db, #2ecc71);
    border: none;
    color: white;
    padding: 14px 20px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 6px;
    cursor: pointer;
    width: 100%;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #2980b9, #27ae60);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

/* Responsive design */
@media (max-width: 768px) {
    .container {
        margin: 20px 15px;
        padding: 20px;
    }
    
    h2 {
        font-size: 22px;
    }
    
    .form-control {
        padding: 10px 12px;
    }
}


.form-group {
    animation: fadeIn 0.5s ease forwards;
}



</style>