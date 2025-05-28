@extends('frontend.layouts.master')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-6">📈 Tiến độ học tập</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white shadow rounded-xl p-4 border">
            <p class="text-sm text-gray-500">Tín chỉ đã hoàn thành</p>
<p class="text-2xl font-bold">{{ $completedCredits }} / {{ $totalCredits }}</p>
        </div>
        <div class="bg-white shadow rounded-xl p-4 border">
            <p class="text-sm text-gray-500">GPA hiện tại</p>
            <p class="text-2xl font-bold">{{ number_format($gpa, 2) }}</p>
        </div>
        <div class="bg-white shadow rounded-xl p-4 border">
            <p class="text-sm text-gray-500">% Hoàn thành</p>
            <p class="text-2xl font-bold">{{ $percentComplete }}%</p>
        </div>
    </div>

    <div class="mb-4">
        <h3 class="text-xl font-semibold mb-2">📚 Danh sách môn đã hoàn thành</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded-lg shadow">
                <thead class="bg-gray-100 text-gray-600 text-left">
                    <tr>
                        <th class="px-4 py-2"></th>
                        <th class="px-4 py-2">Môn học</th>
                        <th class="px-4 py-2">Điểm BP </th>
                        <th class="px-4 py-2">Điểm thi lần 1 </th>
                        <th class="px-4 py-2">Điểm thi lần 2 </th>
                        <th class="px-4 py-2">Điểm tổng </th>
                        <th class="px-4 py-2">Điểm chữ </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($completedEnrollments as $index => $enrollment)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $index + 1 }}</td>
                            <td class="px-4 py-2">{{ optional(optional($enrollment->phanCong)->hocPhan)->title ?? 'Không có môn học' }} </td>

                            <td class="px-4 py-2">{{ optional($enrollment->enrollResult)->DiemBP ?? 'Không có điểm' }}</td>
                            <td class="px-4 py-2">{{ optional($enrollment->enrollResult)->Thi1 ?? 'Không có điểm' }}</td>
                            <td class="px-4 py-2">{{ optional($enrollment->enrollResult)->Thi2 ?? 'Không có điểm' }}</td>
                            <td class="px-4 py-2">{{ optional($enrollment->enrollResult)->DiemMax ?? 'Không có điểm' }}</td>
                            <td class="px-4 py-2">{{ optional($enrollment->enrollResult)->DiemChu ?? 'Không có điểm' }}</td>
                            </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-4 text-center text-gray-500">Chưa có môn học nào được hoàn thành.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mb-4 mt-8">
    <h3 class="text-xl font-semibold mb-2">📝 Thống kê điểm danh theo học phần</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border rounded-lg shadow">
            <thead class="bg-gray-100 text-gray-600 text-left">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Học phần</th>
                    <th class="px-4 py-2">Số buổi có mặt</th>
                    <th class="px-4 py-2">Số buổi vắng</th>
                    <th class="px-4 py-2">Số buổi trễ</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($hocPhanAttendanceStats as $index => $stat)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $stat['hoc_phan']->title ?? 'Không rõ' }}</td>
                        <td class="px-4 py-2 text-green-600 font-semibold">{{ $stat['present'] }}</td>
                        <td class="px-4 py-2 text-red-600 font-semibold">{{ $stat['absent'] }}</td>
                        <td class="px-4 py-2 text-yellow-600 font-semibold">{{ $stat['late'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">Chưa có dữ liệu điểm danh.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
@endsection
