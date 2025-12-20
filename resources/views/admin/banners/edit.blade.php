@extends('admin.layouts.app')

@section('title', 'Edit Offer')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.banners.index') }}" class="btn btn-outline-secondary">&larr; Back to Offers</a>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h4 class="mb-0">Edit Offer</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Offer Title *</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $banner->title) }}" required>
                    @error('title')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Display Order *</label>
                    <input type="number" name="order" class="form-control" value="{{ old('order', $banner->order) }}" required>
                    @error('order')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Offer Description *</label>
                <textarea name="description" class="form-control" rows="2" required>{{ old('description', $banner->description) }}</textarea>
                @error('description')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Banner Image (Optional)</label>
                @if($banner->image)
                    <div class="mb-2">
                        <img src="{{ asset('banners/' . $banner->image) }}" style="max-width: 300px; height: auto;" class="img-thumbnail">
                    </div>
                @endif
                <input type="file" name="image" class="form-control" accept="image/*">
                <small class="text-muted">Recommended size: 1920x600px. Max 2MB. Leave empty to keep current image.</small>
                @error('image')<div class="text-danger">{{ $message }}</div>@enderror
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Action Button Text *</label>
                    <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $banner->button_text) }}" required>
                    @error('button_text')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Button Link *</label>
                    <input type="text" name="button_link" class="form-control" value="{{ old('button_link', $banner->button_link) }}" required>
                    @error('button_link')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Card Background - Start Color *</label>
                    <input type="color" name="gradient_from" class="form-control" value="{{ old('gradient_from', $banner->gradient_from) }}" required>
                    @error('gradient_from')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Card Background - End Color *</label>
                    <input type="color" name="gradient_to" class="form-control" value="{{ old('gradient_to', $banner->gradient_to) }}" required>
                    @error('gradient_to')<div class="text-danger">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" name="is_active" class="form-check-input" id="is_active" value="1" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Offer</button>
        </form>
    </div>
</div>
@endsection
