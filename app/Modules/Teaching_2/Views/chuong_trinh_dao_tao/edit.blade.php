@extends('backend.layouts.master')

@section('content')
<div class="container">
    <h1>Chỉnh sửa Chương trình Đào tạo</h1>

    {{-- Form chỉnh sửa --}}
    <form action="{{ route('admin.chuong_trinh_dao_tao.update', $programs->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Ngành --}}
        <div class="form-group">
            <label for="nganh_id">Ngành</label>
            <select name="nganh_id" class="form-control" required>
                <option value="" disabled selected>Chọn ngành</option>
                @foreach($nganhList as $nganh)
                    <option value="{{ $nganh->id }}">{{ $nganh->title }}</option>
                @endforeach
            </select>
            @error('nganh_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


        {{-- Tiêu đề --}}
        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $programs->title) }}" required>
            @error('title')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nội dung --}}
        <div class="form-group">
            <label for="content">Nội dung</label>
            <textarea name="content" class="form-control" required>{{ old('content', $programs->content) }}</textarea>
            @error('content')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tổng tín chỉ --}}
        <div class="form-group">
            <label for="tong_tin_chi">Tổng Tín Chỉ</label>
            <input type="number" name="tong_tin_chi" class="form-control" value="{{ old('tong_tin_chi', $programs->tong_tin_chi) }}" required>
            @error('tong_tin_chi')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Trạng thái --}}
        <div class="form-group">
            <label for="status">Trạng thái</label>
            <select name="status" class="form-control">
                <option value="active" {{ $programs->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                <option value="inactive" {{ $programs->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
            </select>
            @error('status')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nút hành động --}}
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.chuong_trinh_dao_tao.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
