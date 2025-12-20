@extends('admin.layouts.app')

@section('title', 'Banners Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Offers & Discounts Management</h2>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">Add New Offer</a>
</div>

<div class="alert alert-info mb-4">
    <i class="bi bi-info-circle me-2"></i>
    <strong>Note:</strong> These offers appear as cards on the homepage. Use gradient colors for attractive backgrounds.
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Preview</th>
                        <th>Offer Title</th>
                        <th>Description</th>
                        <th>Action Button</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($banners as $banner)
                    <tr>
                        <td><strong>{{ $banner->order }}</strong></td>
                        <td>
                            @if($banner->image)
                                <img src="{{ asset('banners/' . $banner->image) }}" style="width: 80px; height: 40px; object-fit: cover;" class="rounded">
                            @else
                                <div style="width: 80px; height: 40px; background: linear-gradient(135deg, {{ $banner->gradient_from }} 0%, {{ $banner->gradient_to }} 100%); border-radius: 5px;"></div>
                            @endif
                        </td>
                        <td>{{ $banner->title }}</td>
                        <td>{{ Str::limit($banner->description, 40) }}</td>
                        <td>{{ $banner->button_text }}</td>
                        <td>
                            <form action="{{ route('admin.banners.toggleStatus', $banner) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-{{ $banner->is_active ? 'success' : 'secondary' }}">
                                    {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this banner?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="bi bi-percent fs-1 mb-2 d-block"></i>
                            No offers found. Create your first offer to display on homepage.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($banners->hasPages())
        <div class="card-footer">
            {{ $banners->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
