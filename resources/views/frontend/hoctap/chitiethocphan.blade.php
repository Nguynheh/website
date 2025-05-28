@extends('frontend.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 bg-light text-dark min-vh-100 p-3 border-end">
            @include('frontend.layouts.sidebar')
        </div>

        <!-- Chi tiết học phần -->
        <div class="col-md-9 col-lg-10">
            <div class="container course-detail-container">
                <h3 class="course-detail-title">Chi tiết học phần</h3>

                @foreach($noidungs as $nd)
                    <div class="card content-card mb-4" data-aos="fade-up" data-aos-duration="1000">
                        <div class="card-header content-card-header">
                            <h5 class="content-card-title">{{ $nd->title }}</h5>
                        </div>
                        <div class="card-body content-card-body">
                            <div class="detail-item mb-2">
                                <span class="detail-label fw-semibold">Nội dung:</span>
                                <p class="detail-value">{{ $nd->content }}</p>
                            </div>

                            
                            <div class="detail-item mb-3">
                                <span class="detail-label fw-semibold">Tài nguyên đã tải lên:</span>
                                @php
                                    $resourceIds = [];
                                    if (!empty($nd->resources)) {
                                        $decoded = json_decode($nd->resources, true);
                                        if (is_array($decoded)) {
                                            if (isset($decoded['resource_ids']) && is_array($decoded['resource_ids'])) {
                                                $resourceIds = $decoded['resource_ids'];
                                            } elseif (array_keys($decoded) === range(0, count($decoded) - 1)) {
                                                $resourceIds = $decoded;
                                            }
                                        }
                                    }
                                @endphp

                                @if (!empty($resourceIds))
                                    <ul class="resource-list ps-3">
                                        @foreach ($resourceIds as $resourceId)
                                            @php
                                                $resource = \App\Modules\Resource\Models\Resource::find($resourceId);
                                            @endphp
                                            @if ($resource)
                                                <li class="resource-item">
                                                    <a href="{{ asset($resource->url) }}" target="_blank" class="resource-link text-decoration-none">
                                                        📄 {{ $resource->file_name }}
                                                    </a>
                                                </li>
                                            @else
                                                <li class="resource-item text-danger">
                                                    ⚠️ Tài nguyên với ID {{ $resourceId }} không tồn tại.
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="no-resource fst-italic">Không có tài nguyên nào.</p>
                                @endif
                            </div>

                            <div>
<a href="{{ route('frontend.hoctap.assignments', ['phancong_id' => $nd->phancong_id ?? 0]) }}" class="btn assignment-btn">
    Làm bài tập
</a>
                                <a href="{{ route('frontend.hoctap.chat', $nd->phancong_id ?? 0) }}" class="btn chat-btn ms-2">
                                    💬 Chat lớp học phần
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.assignment-btn {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}
.assignment-btn:hover {
    background-color: #0056b3;
    color: white;
}
.chat-btn {
    background-color: #28a745;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}
.chat-btn:hover {
    background-color: #1e7e34;
    color: white;
}
.detail-label {
    font-weight: 600;
}
.resource-list {
    list-style: none;
    padding-left: 0;
}
.resource-item {
    margin-bottom: 0.25rem;
}
</style>
@endpush

@push('scripts')
<!-- Liên kết các thư viện CSS/JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet" />

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.24/dist/sweetalert2.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        AOS.init();
    });
</script>
@endpush
