@extends('admin.layouts.app')

@section('title', 'Edit Hero Banner')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Hero Banner</h1>
    <p class="page-subtitle">Update hero banner details</p>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.hero-banners.update', $heroBanner) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $heroBanner->title) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Order</label>
                        <input type="number" name="order" class="form-control" value="{{ old('order', $heroBanner->order) }}" required>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3">{{ old('description', $heroBanner->description) }}</textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Current Media</label>
                <div class="mb-2">
                    @if($heroBanner->media_type === 'video')
                        <video src="{{ $heroBanner->media_path }}" style="max-width:300px;height:150px;object-fit:cover;" controls></video>
                    @else
                        <img src="{{ $heroBanner->media_path }}" style="max-width:300px;height:150px;object-fit:cover;" />
                    @endif
                </div>
                <label class="form-label">Replace Media (Optional)</label>
                <input type="file" name="media" class="form-control" accept="image/*,video/*">
                <div class="form-text">Leave empty to keep current media</div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Button Text</label>
                        <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $heroBanner->button_text) }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Button Link</label>
                        <input type="url" name="button_link" class="form-control" value="{{ old('button_link', $heroBanner->button_link) }}">
                    </div>
                </div>
            </div>
            
            <div class="form-check mb-3">
                <input type="checkbox" name="is_active" value="1" class="form-check-input" id="active" {{ $heroBanner->is_active ? 'checked' : '' }}>
                <label class="form-check-label" for="active">Active</label>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Update Banner</button>
                <a href="{{ route('admin.hero-banners.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection