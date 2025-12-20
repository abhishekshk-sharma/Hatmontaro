@extends('admin.layouts.app')

@section('title', 'Page Banners')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Page Banners</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Page Type</th>
                            <th>Page</th>
                            <th>Title</th>
                            <th>Banner Image</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($banners as $banner)
                        <tr>
                            <td>
                                <span class="badge bg-{{ $banner->page_type == 'category' ? 'primary' : 'success' }}">
                                    {{ ucfirst($banner->page_type) }}
                                </span>
                            </td>
                            <td>{{ $banner->page_identifier ?? 'AI Recommended' }}</td>
                            <td>{{ $banner->title }}</td>
                            <td>
                                @if($banner->banner_image)
                                    <img src="{{ $banner->banner_image_url }}" alt="Banner" class="img-thumbnail" style="width: 60px; height: 40px; object-fit: cover;">
                                @else
                                    <span class="text-muted">No image</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $banner->is_active ? 'success' : 'secondary' }}">
                                    {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.page-banners.edit', $banner) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection