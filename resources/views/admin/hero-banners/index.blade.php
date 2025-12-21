@extends('admin.layouts.app')

@section('title', 'Hero Banners')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Hero Banners</h1>
            <p class="page-subtitle">Manage homepage hero banners</p>
        </div>
        <a href="{{ route('admin.hero-banners.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Add Hero Banner
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Preview</th>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($heroBanners as $banner)
                    <tr>
                        <td>
                            @if($banner->media_type === 'video')
                                <video src="{{ $banner->media_path }}" style="width:80px;height:50px;object-fit:cover;" muted></video>
                            @else
                                <img src="{{ $banner->media_path }}" style="width:80px;height:50px;object-fit:cover;" />
                            @endif
                        </td>
                        <td>{{ $banner->title ?: 'No Title' }}</td>
                        <td>
                            <span class="badge bg-{{ $banner->media_type === 'video' ? 'info' : 'success' }}">
                                {{ ucfirst($banner->media_type) }}
                            </span>
                        </td>
                        <td>{{ $banner->order }}</td>
                        <td>
                            <span class="badge bg-{{ $banner->is_active ? 'success' : 'secondary' }}">
                                {{ $banner->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.hero-banners.edit', $banner) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.hero-banners.destroy', $banner) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this banner?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="bi bi-image fs-1 text-muted mb-2 d-block"></i>
                            No hero banners found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection