@extends('frontend.layouts.master')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6">👤 Hồ sơ cá nhân</h2>

    <div class="bg-white shadow rounded-xl p-6 flex gap-6">
        <div class="flex flex-col items-center">
            <img src="{{ auth()->user()->photo ? asset(auth()->user()->photo) : asset('images/default-avatar.png') }}"
                 alt="Avatar"
                 class="w-32 h-32 rounded-full object-cover border shadow mb-4">

            <form action="{{ route('profile.avatar') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label class="cursor-pointer text-sm text-blue-600 hover:underline">
                    Thay ảnh
                    <input type="file" name="avatar" accept="image/*" class="hidden" onchange="this.form.submit()">
                </label>
            </form>
            <!-- Thay đổi mật khẩu - Modal -->
    <button id="openPasswordModal" class="mt-6 bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
        Đổi mật khẩu
    </button>

        </div>

        <div class="flex-1">
            <form action="{{ route('profile.update') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                <div>
                    <label class="block text-sm">Họ tên</label>
@if (isset($student) && $student->user)
    <input name="full_name" value="{{ old('full_name', $student->user->full_name) }}" class="w-full border rounded p-2">
@elseif (Auth::check())
    <input name="full_name" value="{{ old('full_name', Auth::user()->full_name) }}" class="w-full border rounded p-2">
@else
    <input name="full_name" value="" class="w-full border rounded p-2">
@endif                   
 @error('full_name')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <div>
    <label class="block text-sm">
        @if (Auth::user()->role == 'teacher')
            Mã số giảng viên
        @elseif (Auth::user()->role == 'student')
            Mã số sinh viên
        @else
            Mã số
        @endif
    </label>

    @if (Auth::check())
        @if (Auth::user()->role == 'teacher')
            <input name="mgv" value="{{ old('mgv', Auth::user()->teacher->mgv) }}" class="w-full border rounded p-2" readonly>
        @elseif (Auth::user()->role == 'student')
            <input name="mssv" value="{{ old('mssv', Auth::user()->student->mssv ?? 'N/A') }}" class="w-full border rounded p-2" readonly>
        @else
            <input name="id" value="Unknown role" class="w-full border rounded p-2" readonly>
        @endif
    @endif
</div>


                <div>
                    <label class="block text-sm">Email</label>
@if (Auth::check())
    @if (Auth::user()->role == 'teacher')
        <input name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full border rounded p-2">
    @elseif (Auth::user()->role == 'student')
        <input name="email" value="{{ old('email', optional($student)->user->email) }}" class="w-full border rounded p-2">
    @else
        <input name="email" value="Unknown role" class="w-full border rounded p-2">
    @endif
@endif
                </div>

                <div>
                    <label class="block text-sm">Số điện thoại</label>
<input name="phone" 
       value="{{ old('phone', (Auth::user()->role == 'teacher' ? Auth::user()->phone : optional($student)->user->phone)) }}" 
       class="w-full border rounded p-2">
                </div>

                <div>
                    <label class="block text-sm">Mô tả</label>
                    <textarea name="description" rows="3" class="w-full border rounded p-2">{{ old('description', Auth::user()->description) }}</textarea>
                </div>

                <div class="col-span-2 text-right mt-4">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>

    
    <!-- Modal -->
    <div id="passwordModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-8 shadow-xl w-96">
            <h3 class="text-xl font-semibold mb-4">🔐 Đổi mật khẩu</h3>

            <form action="{{ route('profile.password') }}" method="POST">
                @csrf
                <div>
                    <label class="block text-sm">Mật khẩu cũ</label>
                    <input type="password" name="current_password" class="w-full border rounded p-2" required>
                    @error('current_password')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm">Mật khẩu mới</label>
                    <input type="password" name="new_password" class="w-full border rounded p-2" required>
                    @error('new_password')
                        <div class="text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm">Xác nhận mật khẩu mới</label>
                    <input type="password" name="new_password_confirmation" class="w-full border rounded p-2" required>
                </div>

                <div class="text-right mt-4">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                        Đổi mật khẩu
                    </button>
                </div>
            </form>

            <button id="closePasswordModal" class="absolute top-2 right-2 text-gray-600 hover:text-black">
                &times;
            </button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Open the modal
    document.getElementById('openPasswordModal').addEventListener('click', function() {
        document.getElementById('passwordModal').classList.remove('hidden');
    });

    // Close the modal
    document.getElementById('closePasswordModal').addEventListener('click', function() {
        document.getElementById('passwordModal').classList.add('hidden');
    });

    // Close the modal when clicking outside of it
    window.addEventListener('click', function(event) {
        if (event.target == document.getElementById('passwordModal')) {
            document.getElementById('passwordModal').classList.add('hidden');
        }
    });
</script>
@endsection
